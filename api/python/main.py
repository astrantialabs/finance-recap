import math
import json

from excel import Excel
from pymongo import MongoClient
from dotenv import dotenv_values

class Main():
    def main():
        wb_sumarry_recap = Excel("./api/excel/Rekap Fisik dan Keuangan Test.xlsx", 1)
        value = wb_sumarry_recap.get_value_multiple_2d("B6", "D22")

        sumarry_recap_array = []
        percetange_cell = [1, 2]
        for i in range(len(value)):
            for j in range(len(percetange_cell)):
                if(type(value[i][percetange_cell[j]]) in (int, float)): 
                    value[i][percetange_cell[j]] = math.trunc(value[i][percetange_cell[j]] * 100)

                if(type(value[i][percetange_cell[j]]) == str): 
                    if(value[i][percetange_cell[j]] == "#REF!"):
                        value[i][percetange_cell[j]] = None


            temp_sumarry_recap_dictionary = {
                "id": i + 1,
                "activity": value[i][0],
                "physical": value[i][1],
                "finance": value[i][2]
            }

            sumarry_recap_array.append(temp_sumarry_recap_dictionary)


        json_object = json.dumps(sumarry_recap_array, indent = 4)

        with open("./api/json/sumarry_recap.json", "w") as outfile:
            outfile.write(json_object)


    def get_cluster(mongoDBURI):
        cluster = MongoClient(mongoDBURI)
        return cluster


    def get_database(mongoDBURI, database_name):
        db = Main.get_cluster(mongoDBURI)[database_name]
        return db

    
    def get_collection(mongoDBURI, database_name, collection_name):
        collection = Main.get_database(mongoDBURI, database_name)[collection_name]
        return collection


    def summary_upload(mongoDBURI, database_name, collection_name):
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
    config = dotenv_values("./api/.env")

    Main.main()
    Main.summary_upload(config.get("APIdbURI"), "DisnakerFinanceRecap", "summary_recaps")