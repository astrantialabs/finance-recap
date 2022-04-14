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


class Main(Database):
    def main():
        config = dotenv_values("./api/.env")

        Main.get_data("./api/excel/Rekap Fisik dan Keuangan Test.xlsx", 1, "B6", "D22", [1, 2], ["activity", "physical", "finance"], "./api/json/secretariat_summary_recap.json")
        # Main.upload_sumarry(config.get("APIdbURI"), "DisnakerFinanceRecap", "summary_recaps")


    def get_data(excel_path, active_sheet, start_range, end_range, percentage_cell, attribute, json_path):
        wb_data = Excel(excel_path, active_sheet)
        value = wb_data.get_value_multiple_2d(start_range, end_range)

        data_array = []
        for i in range(len(value)):
            for j in range(len(percentage_cell)):
                if(type(value[i][percentage_cell[j]]) in (int, float)): 
                    value[i][percentage_cell[j]] = math.trunc(value[i][percentage_cell[j]] * 100)

                if(type(value[i][percentage_cell[j]]) == str): 
                    if(value[i][percentage_cell[j]] == "#REF!"):
                        value[i][percentage_cell[j]] = None


            temp_data_dictionary = {
                "id": i + 1
            }

            for j in range(len(attribute)):
                temp_data_dictionary[attribute[j]] = value[i][j]

    
            data_array.append(temp_data_dictionary)


        json_object = json.dumps(data_array, indent = 4)

        with open(json_path, "w") as outfile:
            outfile.write(json_object)


    def upload_sumarry(mongoDBURI, database_name, collection_name):
        collection = Main.get_collection(mongoDBURI, database_name, collection_name)

        data = json.load(open('./api/json/sumarry_recap.json'))
        for i in range(len(data)):
            update_id = data[i].get("id")
            update_activity = data[i].get("activity")
            update_physical = data[i].get("physical")
            update_finance = data[i].get("finance")

            # collection.find_one_and_update({"id": i+1}, {"$set" :{"activity" : "Test", "physical" : 100, "finance" : 100}})
            collection.find_one_and_update({"id": update_id}, {"$set" :{"activity" : update_activity, "physical" : update_physical, "finance" : update_finance}})

        
if(__name__ == "__main__"):
    Main.main()