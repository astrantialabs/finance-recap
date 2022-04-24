import PySimpleGUI as sg

from operator import itemgetter
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


class Main(Database):
    def main():
        mongoDBURI = dotenv_values("./api/.env").get("APIdbURI") # path
        database_name = "DisnakerFinanceRecap"
        collection_name = "settings"

        collection = Main.get_collection(mongoDBURI, database_name, collection_name)

        data = list(collection.find({}))

        data_count = 0
        detail_count = 0
        
        current_data = data[data_count]
        current_data_detail = current_data.get("detail")[detail_count]

        current_data_attribute = ["name", "start_range", "end_range"]
        current_data_detail_attribute = ["active_sheet", "start_range", "end_range", "attribute"]\

        button_width = 15
        button_height = 1

        list_data = [
            division.get("name") for division in data
        ]

        layout = [
            [sg.Listbox(list_data, default_values=data[data_count].get("name"), key="listbox", enable_events=True, size=(100, 4), select_mode="single")],
            
            [sg.Text("Division")],
            [sg.InputText(default_text=current_data.get("id"), size=(100, 1), key="data_id", enable_events=True, readonly=True)],
            [[sg.InputText(default_text=current_data.get(attribute), size=(100, 1), key=f"data_{attribute}", enable_events=True)] for attribute in current_data_attribute],

            [sg.Text("Detail")],
            [sg.InputText(default_text=current_data_detail.get("id"), size=(100, 1), key="detail_id", enable_events=True, readonly=True)],
            [[sg.InputText(default_text=current_data_detail.get(attribute), size=(100, 1), key=f"detail_{attribute}", enable_events=True)] for attribute in current_data_detail_attribute],
            
            [
                sg.Button("Previous", key="previous_button", enable_events=True, disabled = True, size=(button_width, button_height)),
                sg.Button("Next", key="next_button", enable_events=True, size=(button_width, button_height)),
                sg.Button("Save", key="save_button", enable_events=True, size=(button_width, button_height))
            ],
            [
                sg.Button("Add Division", key="add_division_button", enable_events=True, size=(button_width, button_height)),
                sg.Button("Delete Division", key="delete_division_button", enable_events=True, size=(button_width, button_height))
            ],
            [
                sg.Button("Add Detail", key="add_detail_button", enable_events=True, size=(button_width, button_height)),
                sg.Button("Delete Detail", key="delete_detail_button", enable_events=True, size=(button_width, button_height))
            ]
        ]

        window = sg.Window("Excel Attribute Editor", layout, size=(435, 475))

        while True:
            event, values = window.read()
            if(event == sg.WINDOW_CLOSED):
                break

            elif(event == "listbox"):
                name = values["listbox"][0]
                
                for i in range(len(data)):
                    if(data[i].get("name") == name):
                        data_count = i
                        detail_count = 0


                Main.update_data(window, data, current_data_attribute, current_data_detail_attribute, data_count, detail_count)

            elif(event == "previous_button" or event == "next_button"):
                if(event == "previous_button" and detail_count != 0):
                    detail_count -= 1

                elif(event == "next_button" and detail_count != len(data[data_count].get("detail"))-1):
                    detail_count += 1 

                Main.update_data(window, data, current_data_attribute, current_data_detail_attribute, data_count, detail_count)

            elif(event == "save_button"):
                confirmation_layout = [
                    [sg.Text("Are you sure?", font=12)],
                    [
                        sg.Button("Yes", key="yes_button", enable_events=True, size=(8, 2), button_color="#00CC00"),
                        sg.Button("No", key="no_button", enable_events=True, size=(8, 2), button_color="#CC0000")
                    ]
                ]

                confirmation_window = sg.Window("Confirm", confirmation_layout, size=(225, 100), element_justification='c', keep_on_top=True)

                while True:
                    confirmation_event, confirmation_values = confirmation_window.read()
                    if(confirmation_event == sg.WINDOW_CLOSED or confirmation_event == "no_button"):
                        break

                    elif(confirmation_event == "yes_button"):
                        list_of_data = []
                        list_of_data.append(values["data_id"])
                        for attribute in current_data_attribute:
                            list_of_data.append(values[f"data_{attribute}"])


                        list_of_detail = []
                        list_of_detail.append(values["detail_id"])
                        for attribute in current_data_detail_attribute:
                            list_of_detail.append(values[f"detail_{attribute}"])


                        has_null_data = False
                        for attribute in list_of_data + list_of_detail:
                            if(attribute == "null"):
                                has_null_data = True


                        if(not has_null_data):
                            list_of_data[0] = int(list_of_data[0])
                            list_of_data[1] = str(list_of_data[1])
                            list_of_data[2] = str(list_of_data[2])
                            list_of_data[3] = str(list_of_data[3])

                            list_of_detail[0] = int(list_of_detail[0])
                            list_of_detail[1] = int(list_of_detail[1])
                            list_of_detail[2] = str(list_of_detail[2])
                            list_of_detail[3] = str(list_of_detail[3])
                            list_of_detail[4] = (list_of_detail[4].replace("(", "").replace(")", "").replace(",", "")).split(" ")

                            for i in range(len(list_of_detail[4])):
                                if(list_of_detail[4][i].isdecimal()):
                                    list_of_detail[4][i] = int(list_of_detail[4][i])


                            data[data_count]["id"] = list_of_data[0]
                            for i, attribute in enumerate(current_data_attribute):
                                data[data_count][attribute] = list_of_data[i+1]


                            data[data_count].get("detail")[detail_count]["id"] = list_of_detail[0]
                            for i, attribute in enumerate(current_data_detail_attribute):
                                data[data_count].get("detail")[detail_count][attribute] = list_of_detail[i+1]


                        Main.update_db(data, collection)

                        Main.update_listbox(window, data, data_count)

                        break


                confirmation_window.close()

            elif(event == "add_division_button"):
                new_id = len(data) + 1
                for i in range(len(data)):
                    if(data[i].get("id") != i+1):
                        new_id = i+1
                        break


                new_division_dictionary = {
                    "id": new_id,
                    "name": "null",
                    "start_range": "null",
                    "end_range": "null",
                    "detail": [
                        {
                            "id": 1,
                            "active_sheet": "null",
                            "start_range": "null",
                            "end_range": "null",
                            "attribute": "null"
                        }
                    ]
                }

                data_count = new_id - 1
                detail_count = 0

                data.append(new_division_dictionary)
                data = sorted(data, key=itemgetter('id'))
                
                Main.update_db(data, collection)

                Main.update_listbox(window, data, data_count)
                Main.update_data(window, data, current_data_attribute, current_data_detail_attribute, data_count, detail_count)

            elif(event == "delete_division_button"):
                del data[data_count]
                data_count = len(data) - 1
                detail_count = 0

                Main.delete_db(data_count, collection)
                Main.update_db(data, collection)

                Main.update_listbox(window, data, data_count)
                Main.update_data(window, data, current_data_attribute, current_data_detail_attribute, data_count, detail_count)              

            elif(event == "add_detail_button"):
                new_id = len(data[data_count].get("detail")) + 1
                for i in range(len(data[data_count].get("detail"))):
                    if(data[data_count].get("detail")[i].get("id") != i+1):
                        new_id = i+1
                        break

                new_detail_dictionary = {
                    "id": new_id,
                    "active_sheet": "null",
                    "start_range": "null",
                    "end_range": "null",
                    "attribute": "null"
                }

                detail_count = new_id - 1

                data[data_count].get("detail").append(new_detail_dictionary)
                data[data_count]["detail"] = sorted(data[data_count].get("detail"), key=itemgetter('id'))
                
                Main.update_db(data, collection)

                Main.update_data(window, data, current_data_attribute, current_data_detail_attribute, data_count, detail_count)

            elif(event == "delete_detail_button"):
                del data[data_count].get("detail")[detail_count]
                detail_count = len(data[data_count].get("detail")) - 1

                Main.update_db(data, collection)

                Main.update_listbox(window, data, data_count)
                Main.update_data(window, data, current_data_attribute, current_data_detail_attribute, data_count, detail_count)

            if(detail_count == 0):
                window["previous_button"].update(disabled = True)

            if(detail_count != 0):
                window["previous_button"].update(disabled = False)

            if(detail_count == len(data[data_count].get("detail"))-1):
                window["next_button"].update(disabled = True)

            if(detail_count != len(data[data_count].get("detail"))-1):
                    window["next_button"].update(disabled = False)

            if(len(data) == 1):
                window["delete_division_button"].update(disabled = True)

            if(len(data) != 1):
                window["delete_division_button"].update(disabled = False)
            
            if(len(data[data_count].get("detail")) == 1):
                window["delete_detail_button"].update(disabled = True)

            if(len(data[data_count].get("detail")) != 1):
                window["delete_detail_button"].update(disabled = False)
            
        window.close()
    

    def update_listbox(window, data, data_count):
        window["listbox"].update([division.get("name") for division in data])
        window["listbox"].update(set_to_index=[data_count], scroll_to_index=data_count)


    def update_data(window, data, current_data_attribute, current_data_detail_attribute, data_count, detail_count):
        window["data_id"].update(data[data_count].get("id"))

        for attribute in current_data_attribute:
            window[f"data_{attribute}"].update(data[data_count].get(attribute))


        if(type(data[data_count].get("detail")[detail_count]) == dict):
            window["detail_id"].update(data[data_count].get("detail")[detail_count].get("id"))

            for attribute in current_data_detail_attribute:
                window[f"detail_{attribute}"].update(data[data_count].get("detail")[detail_count].get(attribute))


        elif(type(data[data_count].get("detail")[detail_count]) != dict):
            window["detail_id"].update(detail_count + 1)

            for attribute in current_data_detail_attribute:
                window[f"detail_{attribute}"].update("null")


    def update_db(data, collection):  
        for i in range(len(data)):
            update_id = data[i].get("id")
            update_dictionary = data[i]

            collection.find_one_and_update({"id": update_id}, {"$set" : update_dictionary }, upsert=True)


    def delete_db(data_count, collection):
        collection.delete_one({"id": data_count+2})

if(__name__ == "__main__"):
    Main.main()