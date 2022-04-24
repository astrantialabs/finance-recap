from django.shortcuts import render
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
    def get_all_setting_data():
        mongoDBURI = dotenv_values("./api/.env").get("APIdbURI") # path
        database_name = "DisnakerFinanceRecap"
        collection_name = "settings"
        setting_collection = Database.get_collection(mongoDBURI, database_name, collection_name)

        setting_data = list(setting_collection.find({}))

        return setting_data


# Create your views here.

def home(request):
    setting_data = Main.get_all_setting_data()

    return render(request, 'app/index.html', { 'title': 'Home', 'setting_data': setting_data })