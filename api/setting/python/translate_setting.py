import json

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


class Main():
    def main():
        # in_path = "api/setting/json/untranslated_dummy_setting.json"
        # out_path = "api/setting/json/dummy_setting.json"

        in_path = "api/setting/json/untranslated_setting.json"
        out_path = "api/setting/json/setting.json"

        data = json.load(open(in_path))

        dictionary_array = []
        for i in range(len(data)):
            
            detail_array = []
            for j in range(len(data[i][3])):
                if(type(data[i][3][j]) == list):
                    temp_detail = {
                        "id": j + 1,
                        "active_sheet": data[i][3][j][0],
                        "start_range": data[i][3][j][1],
                        "end_range": data[i][3][j][2],
                        "attribute": data[i][3][j][3]
                    }

                elif(type(data[i][3][j]) != list):
                    temp_detail = None

                detail_array.append(temp_detail)


            temp_dictionary = {
                "id": i + 1,
                "name": data[i][2],
                "start_range": data[i][0],
                "end_range": data[i][1],
                "detail": detail_array
            }

            dictionary_array.append(temp_dictionary)


        json_object = json.dumps(dictionary_array, indent = 4)
        with open(out_path, "w") as outfile:
            outfile.write(json_object)


    def update():
        mongoDBURI = dotenv_values("./api/.env").get("APIdbURI") # path
        database_name = "DisnakerFinanceRecap"
        collection_name = "settings"

        collection = Database.get_collection(mongoDBURI, database_name, collection_name)
        
        data = json.load(open("./api/setting/json/setting.json"))
        for i in range(len(data)):
            update_id = data[i].get("id")
            update_dictionary = data[i]

            collection.find_one_and_update({"id": update_id}, {"$set" : update_dictionary }, upsert=True)


Main.main()
Main.update()