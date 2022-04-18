import math
import json

from excel import Excel
from pymongo import MongoClient
from dotenv import dotenv_values

class Database():
    def get_cluster(mongoDBURI):
        cluster = MongoClient(mongoDBURI)
        return cluster


    def get_database(mongoDBURI, database_name):
        db = Main.get_cluster(mongoDBURI)[database_name]
        return db

    
    def get_collection(mongoDBURI, database_name, collection_name):
        collection = Main.get_database(mongoDBURI, database_name)[collection_name]
        return collection


class Utility():
    def write_json(data, path):
        json_object = json.dumps(data, indent = 4)

        with open(path, "w") as outfile:
            outfile.write(json_object)


    def convert_to_dict(count, value, attribute, percentage_cell=[]):
        for i in range(len(percentage_cell)):
            if(type(value[count][percentage_cell[i]]) in (int, float)): 
                value[count][percentage_cell[i]] = math.trunc(value[count][percentage_cell[i]] * 100)

            if(type(value[count][percentage_cell[i]]) == str): 
                if(value[count][percentage_cell[i]] == "#REF!"):
                    value[count][percentage_cell[i]] = None


        temp_data_dictionary = {
            "id": count + 1
        }

        for i in range(len(attribute)):
            temp_data_dictionary[attribute[i]] = value[count][i]


        return temp_data_dictionary


class Main(Database, Utility):
    def main():
        mongoDBURI = dotenv_values("./api/.env").get("APIdbURI") # path
        database_name = "DisnakerFinanceRecap"
        collection_name = "summary_recaps"
        collection = Main.get_collection(mongoDBURI, database_name, collection_name)
        
        Main.get_summary_data()
        # Main.update_summary_data(collection)
        

    def get_data(path, active_sheet, start_range, end_range):
        wb_data = Excel(path, active_sheet)
        value = wb_data.get_value_multiple_2d(start_range, end_range)

        return value


    def update_data(collection, json_path, attribute):
        data = json.load(open(json_path))
        for i in range(len(data)):
            update_id = data[i].get("id")
            update_dictionary = {}

            for j in range(len(attribute)):
                update_dictionary[attribute[j]] = data[i].get(attribute[j])


            collection.find_one_and_update({"id": update_id}, {"$set" : update_dictionary })


    def get_detail_data(path, active_sheet, start_range, end_range, cell_attribute):
        detail_array = []
        cell_range = [Excel.convert_range(start_range), Excel.convert_range(end_range)]

        cell_attribute_index = 0
        cell_attribute_count = 0
        
        detail_range = []
        expenses_range = []

        i = cell_range[0][1]
        while i < cell_range[1][1] + 2:
            if(cell_attribute_count == cell_attribute[cell_attribute_index]):  
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


        for i in range(len(detail_range)):
            expenses_array = []
            for j in range(len(detail_range[i][2])):
                physical_value = Main.get_data(path, active_sheet, detail_range[i][2][j][0], detail_range[i][2][j][1])
                finance_value = Main.get_data(path, active_sheet, detail_range[i][2][j][2], detail_range[i][2][j][3])

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
                

            value = Main.get_data(path, active_sheet, detail_range[i][0], detail_range[i][1])
            del value[0][2:4]

            temp_detail_dictionary = {
                "id": i+1,
                "account": value[0][0],
                "total_finance": value[0][1],
                "monthly_finance": value[0][2:14],
                "expenses": expenses_array
            }

            detail_array.append(temp_detail_dictionary)


        return detail_array


    def get_summary_data():
        path = "./api/backend/excel/Rekap Fisik dan Keuangan Test.xlsx" # path
        percentage_cell = [1, 2]
        attribute = ["activity", "physical", "finance", "detail"]
        summary_parameter = json.load(open("./api/backend/json/setting.json"))

        combined_array = []
        for i in range(len(summary_parameter)):
            value = Main.get_data(path, 1, summary_parameter[i].get("start_range"), summary_parameter[i].get("end_range"))

            activity = []
            for j in range(len(value)):
                if(type(summary_parameter[i].get("detail")[j]) == dict):
                    print(summary_parameter[i].get("detail")[j].get("active_Sheet"), summary_parameter[i].get("detail")[j].get("start_range"), summary_parameter[i].get("detail")[j].get("end_range"), summary_parameter[i].get("detail")[j].get("attribute"))
                    detail = Main.get_detail_data(path, summary_parameter[i].get("detail")[j].get("active_Sheet"), summary_parameter[i].get("detail")[j].get("start_range"), summary_parameter[i].get("detail")[j].get("end_range"), summary_parameter[i].get("detail")[j].get("attribute"))
                
                elif(type(summary_parameter[i].get("detail")[j]) != dict):
                    detail = None

                value[j].append(detail)

                temp_activity_dictionary = Main.convert_to_dict(j, value, attribute, percentage_cell)
                activity.append(temp_activity_dictionary)


            temp_dictionary = {
                "id": i + 1,
                "name": summary_parameter[i].get("name"),
                "activity": activity
            }

            combined_array.append(temp_dictionary)
        
        
        Main.write_json(combined_array, "./api/backend/json/summary_recaps.json") # path


    def update_summary_data(collection):
        attribute = ["name", "activity"]
        
        Main.update_data(collection, "./api/backend/json/summary_recaps.json", attribute) # path


if(__name__ == "__main__"):
    Main.main()