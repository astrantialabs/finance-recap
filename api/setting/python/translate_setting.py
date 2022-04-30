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
    file_path = "api/setting/json"
    in_path = f"{file_path}/untranslated_setting.json"
    out_path = f"{file_path}/setting.json"

    def main():
        settings_data = json.load(open(Main.in_path))

        division_array = []
        for division_index, division_settings in enumerate(settings_data):
            detail_array = []
            for detail_index, detail_settings in enumerate(division_settings[3]):
                temp_detail_dictionary = {
                    "id": detail_index + 1,
                    "active_sheet": detail_settings[0],
                    "start_range": detail_settings[1],
                    "end_range": detail_settings[2],
                    "attribute": detail_settings[3]
                }

                detail_array.append(temp_detail_dictionary)
            

            temp_division_dictionary = {
                "id": division_index + 1,
                "name": division_settings[2],
                "start_range": division_settings[0],
                "end_range": division_settings[1],
                "detail": detail_array
            }

            division_array.append(temp_division_dictionary)


        json_object = json.dumps(division_array, indent = 4)
        with open(Main.out_path, "w") as outfile:
            outfile.write(json_object)

    
    def update():
        mongoDBURI = dotenv_values("./api/.env").get("APIdbURI") # path
        database_name = "DisnakerFinanceRecap"
        collection_name = "settings"

        collection = Database.get_collection(mongoDBURI, database_name, collection_name)
        
        settings_data = json.load(open(Main.out_path))

        for division_settings in settings_data:
            update_id = division_settings.get("id")
            update_dictionary = division_settings

            collection.replace_one({"id": update_id}, update_dictionary, upsert=True)


Main.main()
Main.update()