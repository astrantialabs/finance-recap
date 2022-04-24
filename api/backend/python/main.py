import math
import json
import os
import datetime

from excel import Excel
from pymongo import MongoClient
from dotenv import dotenv_values

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


    def update_data(mongoDBURI, database_name):
        excel_path = "./api/backend/excel/Rekap Fisik dan Keuangan Test.xlsx"

        excel_last_modified = os.path.getmtime(excel_path)
        translated_excel_last_modified = datetime.datetime.fromtimestamp(excel_last_modified).strftime("%Y-%m-%d %H:%M:%S")

        collection_name = "utilities"
        utilities_collection = Database.get_collection(mongoDBURI, database_name, collection_name)

        utilities_collection.find_one_and_update({"id": 1}, {"$set" : {"last_modified": translated_excel_last_modified}})


class Main(Database, Utility):
    def main():
        mongoDBURI = dotenv_values("./api/.env").get("APIdbURI") # path
        database_name = "DisnakerFinanceRecap"
        
        Main.get_summary(mongoDBURI, database_name)
        Main.update_summary(mongoDBURI, database_name)

        # Main.show_summary(mongoDBURI, database_name)
        
        Utility.update_data(mongoDBURI, database_name)


    def get_summary(mongoDBURI, database_name):
        collection_name = "settings"

        settings_collection = Main.get_collection(mongoDBURI, database_name, collection_name)
        settings_data = list(settings_collection.find({}))
        
        excel_path = "./api/backend/excel/Rekap Fisik dan Keuangan Test.xlsx" # path
        wb_summary = Excel(excel_path, 1)

        division_array = Main.get_division(settings_data, wb_summary)

        Main.write_json(division_array, "./api/backend/json/summary_recaps.json")


    def get_division(settings_data, wb_summary):
        division_array = []
        for division_count, division_data in enumerate(settings_data):
            activity_array = Main.get_activity(division_data, wb_summary)

            temp_division_dictionary = {
                "id": division_count+1,
                "name": division_data.get("name"),
                "activity": activity_array
            }

            division_array.append(temp_division_dictionary)

        return division_array

    
    def get_activity(division_data, wb_summary):
        activity_array = []
        wb_summary.change_sheet(1)
        activity_value = wb_summary.get_value_multiple_2d(division_data.get("start_range"), division_data.get("end_range"))
        for activity_count, activity_data in enumerate(activity_value):
            detail_array = Main.get_detail(division_data, activity_count, wb_summary)

            for i in range(2):
                if(type(activity_data[i+1]) in (int, float)):
                    activity_data[i+1] = math.trunc(activity_data[i+1] * 100)


            temp_activity_dictionary = {
                "id": activity_count+1,
                "activity": activity_data[0],
                "physical": activity_data[1],
                "finance": activity_data[2],
                "detail": detail_array
            }

            activity_array.append(temp_activity_dictionary)


        return activity_array


    def get_detail(division_data, activity_count, wb_summary):
        detail_setting = division_data.get("detail")[activity_count]
        if(type(detail_setting) == dict):
            detail_array = []
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

            
            wb_summary.change_sheet(detail_setting.get("active_sheet"))
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
                        temp_physical_monthly = [physical_value[0][2:14][k], physical_value[1][2:14][k]]
                        physical_monthly.append(temp_physical_monthly)

                    
                    finance_monthly = []
                    for k in range(len(finance_value[0][2:14])):
                        temp_finance_monthly = [finance_value[0][2:14][k], finance_value[1][2:14][k]]
                        finance_monthly.append(temp_finance_monthly)


                    temp_expenses_dictionary = {
                        "id": j+1,
                        "name": physical_value[0][0],
                        "physical": {
                            "total": physical_value[0][1],
                            "monthly": physical_monthly
                        },
                        "finance": {
                            "total": finance_value[0][1],
                            "monthly": finance_monthly
                        }
                    }

                    expenses_array.append(temp_expenses_dictionary)
                    

                value = wb_summary.get_value_multiple(detail_range[i][0], detail_range[i][1])
                del value[2:4]

                temp_detail_dictionary = {
                    "id": i+1,
                    "account": value[0],
                    "total_finance": value[1],
                    "monthly_finance": value[2:14],
                    "expenses": expenses_array
                }

                detail_array.append(temp_detail_dictionary)

        
        elif(type(detail_setting) != dict):
            detail_array = None

        return detail_array
    

    def update_summary(mongoDBURI, database_name):
        collection_name = "summary_recaps"
        summary_recaps_collection = Main.get_collection(mongoDBURI, database_name, collection_name)

        data = json.load(open("./api/backend/json/summary_recaps.json")) # path
        for i in range(len(data)):
            update_id = data[i].get("id")
            update_dictionary = {
                "name": data[i].get("name"),
                "activity": data[i].get("activity")
            }

            summary_recaps_collection.find_one_and_update({"id": update_id}, {"$set" : update_dictionary })


    def show_summary(mongoDBURI, database_name):
        collection_name = "summary_recaps"
        collection = Main.get_collection(mongoDBURI, database_name, collection_name)

        all_data = collection.find()
        single_data = collection.find({ "name": "Sekretariat" })
        
        print(all_data)
        print(single_data)


if(__name__ == "__main__"):
    Main.main()