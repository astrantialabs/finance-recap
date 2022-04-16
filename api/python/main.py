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
        mongoDBURI = dotenv_values("./api/.env").get("APIdbURI")
        database_name = "DisnakerFinanceRecap"
        collection_name = "summary_recaps"
        collection = Main.get_collection(mongoDBURI, database_name, collection_name)
        
        Main.get_summary_data()
        Main.update_summary_data(collection)


    def write_json(data, path):
        json_object = json.dumps(data, indent = 4)

        with open(path, "w") as outfile:
            outfile.write(json_object)


    def get_data(path, active_sheet, start_range, end_range, percentage_cell, attribute):
        wb_data = Excel(path, active_sheet)
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

        
        return data_array


    def update_data(collection, json_path, attribute):
        data = json.load(open(json_path))
        for i in range(len(data)):
            update_id = data[i].get("id")
            update_dictionary = {}

            for j in range(len(attribute)):
                update_dictionary[attribute[j]] = data[i].get(attribute[j])
                # update_dictionary[attribute[j]] = 100


            collection.find_one_and_update({"id": update_id}, {"$set" : update_dictionary })


    def get_summary_data():
        path = "./api/excel/Rekap Fisik dan Keuangan Test.xlsx"
        percentage_cell = [1, 2]
        attribute = ["activity", "physical", "finance"]
        summary_parameter = [
            ["B6", "D22", "Sekretariat"],
            ["H6", "J14", "Penta"],
            ["N6", "P8", "Lattas"],
            ["T6", "V11", "HI"]
        ]

        combined_array = []
        for i in range(len(summary_parameter)):
            activity = Main.get_data(path, 1, summary_parameter[i][0], summary_parameter[i][1], percentage_cell, attribute)

            temp_dictionary = {
                "id": i + 1,
                "name": summary_parameter[i],
                "activity": activity
            }

            combined_array.append(temp_dictionary)
        
        
        Main.write_json(combined_array, "./api/json/summary_recaps.json")


    def update_summary_data(collection):
        attribute = ["name", "activity"]
        
        Main.update_data(collection, "./api/json/summary_recaps.json", attribute)


if(__name__ == "__main__"):
    Main.main()