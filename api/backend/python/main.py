import json
import os
import datetime
import timeit
import gridfs

from excel import Excel
from pymongo import MongoClient
from dotenv import dotenv_values
from win32com import client

class Database():
    def get_cluster(mongoDBURI):
        cluster = MongoClient(mongoDBURI)
        return cluster


    def get_database(mongoDBURI, database_name):
        db = Database.get_cluster(mongoDBURI)[database_name]
        return db

    
    def get_collection(mongoDBURI, database_name, collection_name):
        collection = Database.get_database(mongoDBURI, database_name)[collection_name]
        return collection


class Utility():
    def write_json(data, path):
        json_object = json.dumps(data, indent = 4)

        with open(path, "w") as outfile:
            outfile.write(json_object)


    def convert_to_dict(count, value, attribute, percentage_cell=[]):
        for i in range(len(percentage_cell)):
            if(type(value[percentage_cell[i]]) in (int, float)): 
                value[percentage_cell[i]] = round(value[percentage_cell[i]] * 100)

            if(type(value[percentage_cell[i]]) == str): 
                if(value[percentage_cell[i]] == "#REF!"):
                    value[percentage_cell[i]] = None


        temp_data_dictionary = {
            "id": count + 1
        }

        for i in range(len(attribute)):
            temp_data_dictionary[attribute[i]] = value[i]


        return temp_data_dictionary


    def update_data(mongoDBURI, database_name):
        excel_path = Main.env_value.get("ExcelPath")

        excel_last_modified = os.path.getmtime(excel_path)
        translated_excel_last_modified = datetime.datetime.fromtimestamp(excel_last_modified).strftime("%y-%m-%d-%H-%M-%S")

        collection_name = "utilities"
        utilities_collection = Database.get_collection(mongoDBURI, database_name, collection_name)

        update_dictionary = {
            "id": 1,
            "last_modified": translated_excel_last_modified,
            "last_runned": datetime.datetime.now().strftime("%y-%m-%d-%H-%M-%S")
        }

        utilities_collection.replace_one({"id": 1}, update_dictionary, upsert=True)


class File():
    def create_file(mongoDBURI, file_path, data):
        system_path = os.getcwd()
        excel_client_dispatch = client.Dispatch("Excel.Application")

        current_datetime = datetime.datetime.now().strftime("%y-%m-%d-%H-%M-%S")
        excel_file_path = f"{current_datetime}.xlsx"
        pdf_file_path = f"{current_datetime}.pdf"

        if(Main.env_value.get("Status") == "Production"):
            database_phase = "Pro"

        elif(Main.env_value.get("Status") == "Development"):
            database_phase = "Dev"

        for division in data[0].get("divisi"):

            excel_folder_path = f"excel/{division.get('divisi').lower()}"
            full_excel_file_path = f"{file_path}/{excel_folder_path}/{excel_file_path}"
            system_excel_path = os.path.join(system_path, full_excel_file_path)

            pdf_folder_path = f"pdf/{division.get('divisi').lower()}"
            full_pdf_folder_path = f"{file_path}/{pdf_folder_path}/{pdf_file_path}"
            system_pdf_path = os.path.join(system_path, full_pdf_folder_path)

            os.makedirs(f"{file_path}/{excel_folder_path}", exist_ok=True)
            os.makedirs(f"{file_path}/{pdf_folder_path}", exist_ok=True)

            print(f"Membuat    : File {division.get('divisi')}")
            File.create_excel(file_path, division, full_excel_file_path)
            File.create_pdf(system_excel_path, system_pdf_path, excel_client_dispatch)

            print(f"Mengunggah : File {division.get('divisi')}")
            database_name = f"{database_phase}{division.get('divisi')}"
            File.upload_file(mongoDBURI, database_name, current_datetime, full_excel_file_path, full_pdf_folder_path)
            print(f"Selesai    : File {division.get('divisi')}")
            print()

        
        excel_client_dispatch.Application.Quit()


    def create_excel(file_path, division, full_excel_file_path):
        Excel.create_file(full_excel_file_path)

        wb_excel = Excel(full_excel_file_path, 1)
        wb_excel.write_value_singular("A1", f"Divisi: {division.get('divisi')}")
        wb_excel.write_value_multiple("A3", "C3", ["No.", "Sub Kegiatan", "Realisasi"])
        wb_excel.write_value_multiple("c4", "D4", ["Fisik", "Keuangan"])

        wb_excel.merge("A3", "A4")
        wb_excel.merge("B3", "B4")
        wb_excel.merge("C3", "D3")

        row_end_range = 4 + len(division.get('sub_kegiatan'))
        wb_excel.border_multiple("A3", [4, row_end_range], "all", style="thin")
        wb_excel.alignment_multiple("A3", [4, row_end_range], horizontal="center", vertical="center")
        wb_excel.alignment_multiple("B5", [2, row_end_range], horizontal="left", vertical="center", wrap=True)
        wb_excel.font_singular("A1", size=12, bold=True)
        wb_excel.font_multiple("A3", "D4", bold=True)

        for activity_count, activity in enumerate(division.get('sub_kegiatan')):
            activity_physical_value = None
            if(type(activity.get('fisik')) in (int, float)):
                activity_physical_value = f"{activity.get('fisik')}%"

            activity_finance_value = None
            if(type(activity.get('keuangan')) in (int, float)):
                activity_finance_value = f"{activity.get('keuangan')}%"

            activity_value = [
                activity_count + 1,
                activity.get("sub_kegiatan"),
                activity_physical_value,
                activity_finance_value
            ]

            wb_excel.write_value_multiple(
                [1, 5 + activity_count], [4, 5 + activity_count], activity_value)


        wb_excel.active_sheet.column_dimensions["A"].width = 5
        wb_excel.active_sheet.column_dimensions["B"].width = 60
        wb_excel.active_sheet.column_dimensions["C"].width = 11
        wb_excel.active_sheet.column_dimensions["D"].width = 11
        wb_excel.save()


    def create_pdf(system_excel_path, system_pdf_path, excel_client_dispatch):
        sheets = excel_client_dispatch.Workbooks.Open(system_excel_path)
        work_sheets = sheets.Worksheets[0]
    
        work_sheets.ExportAsFixedFormat(0, system_pdf_path)

        sheets.Close(True)


    def upload_file(mongoDBURI, database_name, file_name, excel_path, pdf_path):
        database = Database.get_database(mongoDBURI, database_name)
        
        excel_file = open(excel_path, "rb")
        pdf_file = open(pdf_path, "rb")

        excel_data = excel_file.read()
        pdf_data = pdf_file.read()

        fs = gridfs.GridFS(database)
        fs.put(excel_data, filename=f"{file_name}.xlsx")
        fs.put(pdf_data, filename=f"{file_name}.pdf")


class Main(Database, Utility, File):
    env_value = dotenv_values("./api/.env") # path

    def main():
        try:
            start_time = timeit.default_timer()

            mongoDBURI = Main.env_value.get("APIdbURI")
            if(Main.env_value.get("Status") == "Development"):
                database_name = "Development"

            elif(Main.env_value.get("Status") == "Production"):
                database_name = "DisnakerFinanceRecap"

            Main.get_data(mongoDBURI, database_name)

            # Main.show_data(mongoDBURI, database_name)

            Utility.update_data(mongoDBURI, database_name)

            stop_time = timeit.default_timer()
            elapsed_time = stop_time - start_time

            print(f"Waktu yang berlalu: {elapsed_time} detik")
            print()
            print("Anda bisa menutup programnya...")
            input()

        except:
            print("Program gagal untuk dijalankan...")
            input()


    def get_data(mongoDBURI, database_name):
        collection_name = "settings"

        settings_collection = Main.get_collection(mongoDBURI, database_name, collection_name)
        settings_data = list(settings_collection.find({}))
        
        excel_path = Main.env_value.get("ExcelPath")
        wb_summary = Excel(excel_path, 1)

        division_array = Main.get_division(settings_data, wb_summary)

        recap_array = [
            {
                "id": 1,
                "tahun": 2022,
                "divisi": division_array
            }
        ]


        if(Main.env_value.get("Status") == "Development"):
            Main.write_json(recap_array, Main.env_value.get("JSONPath"))
            data = json.load(open(Main.env_value.get("JSONPath")))
            Main.update_data(mongoDBURI, database_name, data)

            Main.create_file(mongoDBURI, Main.env_value.get("FilePath"), data)            

        elif(Main.env_value.get("Status") == "Production"):
            Main.update_data(mongoDBURI, database_name, recap_array)

            Main.create_file(mongoDBURI, Main.env_value.get("FilePath"), recap_array)


    def get_division(settings_data, wb_summary):
        division_array = []
        for division_count, division_data in enumerate(settings_data):
            print(f"Memproses  : {division_data.get('name')}")
            activity_array = Main.get_activity(division_data, wb_summary)

            division_attribute = ["divisi", "sub_kegiatan"]
            division_value = [str(division_data.get("name")), activity_array]
            temp_division_dictionary = Main.convert_to_dict(division_count, division_value, division_attribute)

            division_array.append(temp_division_dictionary)
            print(f"Selesai    : {division_data.get('name')}")
            print()

        return division_array


    def get_activity(division_data, wb_summary):
        activity_array = []
        wb_summary.change_sheet(1)
        activity_value = wb_summary.get_value_multiple_2d(division_data.get("start_range"), division_data.get("end_range"))
        for activity_count, activity_data in enumerate(activity_value):
            detail_array = Main.get_detail(division_data, activity_count, wb_summary)

            activity_attribute = ["sub_kegiatan", "fisik", "keuangan", "detail"]
            activity_dict_value = [str(activity_data[0]), activity_data[1], activity_data[2], detail_array]
            activity_percentage_cell = [1, 2]
            temp_activity_dictionary = Main.convert_to_dict(activity_count, activity_dict_value, activity_attribute, activity_percentage_cell)

            activity_array.append(temp_activity_dictionary)


        return activity_array


    def get_detail(division_data, activity_count, wb_summary):
        detail_setting = division_data.get("detail")[activity_count]

        print(f"Memproses  : {detail_setting.get('id')} {detail_setting.get('active_sheet')} {detail_setting.get('sheet_name')} {detail_setting.get('start_range')} {detail_setting.get('end_range')} {detail_setting.get('attribute')}")

        detail_array = []
        if("null" not in detail_setting.get("sheet_name")):
            cell_range = [Excel.convert_range(detail_setting.get("start_range")), Excel.convert_range(detail_setting.get("end_range"))]

            cell_attribute_index = 0
            cell_attribute_count = 0

            detail_range = []
            expenses_range = []

            i = cell_range[0][1]
            while i < cell_range[1][1] + 2:
                if(cell_attribute_count == detail_setting.get("attribute")[cell_attribute_index]):  
                    combined_expenses_range = []

                    j = 0
                    while j < len(expenses_range):
                        temp_combined_expenses_range = expenses_range[j] + expenses_range[j+1] 
                        combined_expenses_range.append(temp_combined_expenses_range)

                        j += 2


                    temp_detail.append(combined_expenses_range)
                    detail_range.append(temp_detail)

                    expenses_range = []
                    cell_attribute_count = 0
                    cell_attribute_index += 1

                if(cell_attribute_count == 0):
                    temp_detail = [[cell_range[0][0], i], [cell_range[1][0], i]]

                    cell_attribute_count += 1
                    i += 1
                
                elif(cell_attribute_count != 0):
                    temp_expenses = [[cell_range[0][0], i], [cell_range[1][0], i+1]]
                    expenses_range.append(temp_expenses)

                    cell_attribute_count += 1
                    i += 2        

        
            wb_summary.change_sheet(detail_setting.get("sheet_name"))
            for i in range(len(detail_range)):
                expenses_array = []
                for j in range(len(detail_range[i][2])):
                    physical_value = wb_summary.get_value_multiple_2d(detail_range[i][2][j][0], detail_range[i][2][j][1])
                    finance_value = wb_summary.get_value_multiple_2d(detail_range[i][2][j][2], detail_range[i][2][j][3])

                    del physical_value[0][2:4]
                    del physical_value[1][2:4]
                    del finance_value[0][2:4]
                    del finance_value[1][2:4]

                    physical_monthly = []
                    for k in range(len(physical_value[0][2:14])):
                        temp_physical_monthly_dictionary = {
                            "id": k + 1,
                            "target": {
                                "total": physical_value[0][2:14][k],
                                "note": None
                            },
                            "realisasi": {
                                "total": physical_value[1][2:14][k],
                                "note": None
                            }
                        }

                        physical_monthly.append(temp_physical_monthly_dictionary)


                    finance_monthly = []
                    for k in range(len(finance_value[0][2:14])):
                        temp_finance_monthly_dictionary = {
                            "id": k + 1,
                            "target": {
                                "total": finance_value[0][2:14][k],
                                "note": None
                            },
                            "realisasi": {
                                "total": finance_value[1][2:14][k],
                                "note": None
                            }
                        }

                        finance_monthly.append(temp_finance_monthly_dictionary)


                    temp_expenses_dictionary = {
                        "id": j+1,
                        "biaya": str(physical_value[0][0]),
                        "fisik": {
                            "jumlah_fisik": str(physical_value[0][1]),
                            "jumlah_Kebutuhan_dana": physical_monthly
                        },
                        "keuangan": {
                            "jumlah_anggaran": str(finance_value[0][1]),
                            "jumlah_Kebutuhan_dana": finance_monthly
                        }
                    }

                    expenses_array.append(temp_expenses_dictionary)


                value = wb_summary.get_value_multiple(detail_range[i][0], detail_range[i][1])
                del value[2:4]

                monthly_total = []
                for monthly_value_index, monthly_value in enumerate(value[2:14]):
                    new_value_dict = {
                        "id": monthly_value_index + 1,
                        "total": monthly_value,
                        "note": None
                    }

                    monthly_total.append(new_value_dict)


                temp_detail_dictionary = {
                    "id": 1,
                    "rekening": str(value[0]),
                    "jumlah_fisik_anggaran": value[1],
                    "jumlah_Kebutuhan_dana": monthly_total,
                    "biaya": expenses_array
                }

                detail_array.append(temp_detail_dictionary)


        return detail_array


    def update_data(mongoDBURI, database_name, data):
        print("Mengunggah : Data")

        collection_name = "summary_recaps"
        summary_recaps_collection = Main.get_collection(mongoDBURI, database_name, collection_name)

        for i in range(len(data)):
            update_id = data[i].get("id")
            update_dictionary = {
                "id": update_id,
                "tahun": data[i].get("tahun"),
                "divisi": data[i].get("divisi")
            }

            summary_recaps_collection.replace_one({"id": update_id}, update_dictionary, upsert=True)


        print("Selesai    : Data")
        print()


    def show_data(mongoDBURI, database_name):
        collection_name = "summary_recaps"
        collection = Main.get_collection(mongoDBURI, database_name, collection_name)

        all_data = collection.find({})
        single_data = collection.find({"name": "Sekretariat"})

        print(all_data)
        print(single_data)


if(__name__ == "__main__"):
    Main.main()