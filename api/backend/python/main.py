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


    def convert_to_dict(count, value, attribute, percentage_cell=[]):
        for i in range(len(percentage_cell)):
            if(type(value[percentage_cell[i]]) in (int, float)): 
                value[percentage_cell[i]] = math.trunc(value[percentage_cell[i]] * 100)

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
        translated_excel_last_modified = datetime.datetime.fromtimestamp(excel_last_modified).strftime("%Y-%m-%d %H:%M:%S")

        collection_name = "utilities"
        utilities_collection = Database.get_collection(mongoDBURI, database_name, collection_name)

        utilities_collection.find_one_and_update({"id": 1}, {"$set" : {"last_modified": translated_excel_last_modified}})


class Main(Database, Utility):
    env_value = dotenv_values("./api/.env")

    def main():
        mongoDBURI = Main.env_value.get("APIdbURI")
        database_name = "DisnakerFinanceRecap"
        
        Main.get_data(mongoDBURI, database_name)
        Main.update_data(mongoDBURI, database_name)

        Main.show_data(mongoDBURI, database_name)
        
        Utility.update_data(mongoDBURI, database_name)

        print("Program Has Finished Running")


    def get_data(mongoDBURI, database_name):
        collection_name = "settings"

        settings_collection = Main.get_collection(mongoDBURI, database_name, collection_name)
        settings_data = list(settings_collection.find({}))
        
        excel_path = Main.env_value.get("ExcelPath")
        wb_summary = Excel(excel_path, 1)

        division_array = Main.get_division(settings_data, wb_summary)

        Main.write_json(division_array, Main.env_value.get("JSONPath"))


    def get_division(settings_data, wb_summary):
        division_array = []
        for division_count, division_data in enumerate(settings_data):
            print(f"Processing : {division_data.get('name')}")
            activity_array = Main.get_activity(division_data, wb_summary)

            division_attribute = ["name", "activity"]
            division_value = [division_data.get("name"), activity_array]
            temp_division_dictionary = Main.convert_to_dict(division_count, division_value, division_attribute)

            division_array.append(temp_division_dictionary)
            print(f"Completed  : {division_data.get('name')}")
            print()

        return division_array

    
    def get_activity(division_data, wb_summary):
        activity_array = []
        wb_summary.change_sheet(1)
        activity_value = wb_summary.get_value_multiple_2d(division_data.get("start_range"), division_data.get("end_range"))
        for activity_count, activity_data in enumerate(activity_value):
            detail_array = Main.get_detail(division_data, activity_count, wb_summary)

            activity_attribute = ["activity", "physical", "finance", "detail"]
            activity_dict_value = [activity_data[0], activity_data[1], activity_data[2], detail_array]
            activity_percentage_cell = [1, 2]
            temp_activity_dictionary = Main.convert_to_dict(activity_count, activity_dict_value, activity_attribute, activity_percentage_cell)

            activity_array.append(temp_activity_dictionary)


        return activity_array


    def get_detail(division_data, activity_count, wb_summary):
        detail_setting = division_data.get("detail")[activity_count]
        if(type(detail_setting) == dict):
            print(f"Processing : {detail_setting.get('id')} {detail_setting.get('active_sheet')} {detail_setting.get('start_range')} {detail_setting.get('end_range')} {detail_setting.get('attribute')}")
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

                detail_attribute = ["account", "total_finance", "monthly_finance", "expenses"]
                detail_dict_value = [value[0], value[1], value[2:14], expenses_array]
                temp_detail_dictionary = Main.convert_to_dict(i, detail_dict_value, detail_attribute)

                detail_array.append(temp_detail_dictionary)

        
        elif(type(detail_setting) != dict):
            detail_array = None

        return detail_array
    

    def update_data(mongoDBURI, database_name):
        collection_name = "summary_recaps"
        summary_recaps_collection = Main.get_collection(mongoDBURI, database_name, collection_name)

        data = json.load(open(Main.env_value.get("JSONPath")))
        for i in range(len(data)):
            update_id = data[i].get("id")
            update_dictionary = {
                "name": data[i].get("name"),
                "activity": data[i].get("activity")
            }

            summary_recaps_collection.find_one_and_update({"id": update_id}, {"$set" : update_dictionary })


    def show_data(mongoDBURI, database_name):
        collection_name = "summary_recaps"
        collection = Main.get_collection(mongoDBURI, database_name, collection_name)

        all_data = collection.find({})
        single_data = collection.find({ "name": "Sekretariat" })
        
        print(all_data)
        print(single_data)


if(__name__ == "__main__"):
    Main.main()