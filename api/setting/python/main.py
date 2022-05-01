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

        settings_data = list(collection.find({}))

        division_count = 0
        detail_count = 0

        division_attribute = ["id", "name", "start_range", "end_range"]
        detail_attribute = ["id", "active_sheet", "start_range", "end_range", "attribute"]

        list_of_button = [
            [
                ["Previous", "previous", True],
                ["Next", "next", False],
                ["Save", "save", False]
            ],
            [
                ["Add Division", "add_division", False],
                ["Delete Division", "delete_division", False]
            ],
            [
                ["Add Detail", "add_detail", False],
                ["Delete Detail", "delete_detail", False]
            ]
        ]
        
        list_box_values = [
            division.get("name") for division in settings_data
        ]

        layout = [
            [sg.Listbox(list_box_values, default_values=settings_data[division_count].get("name"), key="listbox", enable_events=True, size=(100, 4), select_mode="single")],

            [sg.Text("Division")],
            [[sg.InputText(default_text=settings_data[division_count].get(attribute), size=(100, 1), key=f"division_{attribute}", enable_events=True)] for attribute in division_attribute],

            [sg.Text("Detail")],
            [[sg.InputText(default_text=settings_data[division_count].get("detail")[detail_count].get(attribute), size=(100, 1), key=f"detail_{attribute}", enable_events=True)] for attribute in detail_attribute],

            [[sg.Button(button[0], key=f"{button[1]}_button", enable_events=True, size=(15, 1), disabled=button[2]) for button in buttons] for buttons in list_of_button]
        ]

        window = sg.Window("Excel Attribute Editor", layout, size=(435, 475))
        
        while True:
            event, values = window.read()

            if(event == sg.WINDOW_CLOSED):
                break
            
            elif(event == "listbox"):
                name = values["listbox"][0]

                for division_index, division in enumerate(settings_data):
                    if(division.get("name") == name):
                        division_count = division_index
                        detail_count = 0


                Main.update(window, settings_data, division_attribute, detail_attribute, division_count, detail_count)
            
            elif(event in ("previous_button", "next_button")):
                if(event == "previous_button" and detail_count != 0):
                    detail_count -= 1

                elif(event == "next_button" and detail_count != len(settings_data[division_count].get("detail")) - 1):
                    detail_count += 1
                
                Main.update(window, settings_data, division_attribute, detail_attribute, division_count, detail_count)

            elif(event == "add_division_button"):
                window.Disable()

                new_id = len(settings_data) + 1
                for division_index, division in enumerate(settings_data):
                    if(division.get("id") != division_index + 1):
                        new_id = division_index + 1
                        break

                list_of_add_division_division_input = [
                    new_id,
                    "Name",
                    "Start Range",
                    "End Range"
                ]
                
                list_of_add_division_detail_input = [
                    1,
                    "Active Sheet",
                    "Start Range",
                    "End Range",
                    "Attribute"
                ]

                list_of_add_division_button = [
                    "Cancel",
                    "Add"
                ]

                add_division_layout = [
                    [sg.Text("Division")],
                    [[sg.InputText(default_text=list_of_add_division_division_input[attribute_index], size=(100, 1), key=f"add_division_division_{attribute}", enable_events=True)] for attribute_index, attribute in enumerate(division_attribute)],

                    [sg.Text("Detail")],
                    [[sg.InputText(default_text=list_of_add_division_detail_input[attribute_index], size=(100, 1), key=f"add_division_detail_{attribute}", enable_events=True)] for attribute_index, attribute in enumerate(detail_attribute)],

                    [sg.Button(button, key=f"{button.lower()}_add_division_button", enable_events=True, size=(12, 1)) for button in list_of_add_division_button]
                ]

                add_division_window = sg.Window("Add Division", add_division_layout, size=(245, 335), keep_on_top=True)

                while True:
                    add_division_event, add_division_values = add_division_window.read()
                    if(add_division_event in (sg.WINDOW_CLOSED, "cancel_add_division_button")):
                        break

                    elif(add_division_event == "add_add_division_button"):
                        add_division_is_valid = True

                        list_of_add_division_division = []
                        for attribute in division_attribute:
                            list_of_add_division_division.append(add_division_values[f"add_division_division_{attribute}"])


                        list_of_add_division_detail = []
                        for attribute in detail_attribute:
                            list_of_add_division_detail.append(add_division_values[f"add_division_detail_{attribute}"])

                        
                        if(list_of_add_division_division[0].isnumeric()):
                            list_of_add_division_division[0] = int(list_of_add_division_division[0])

                        else:
                            add_division_is_valid = False

                        if(list_of_add_division_detail[0].isnumeric()):
                            list_of_add_division_detail[0] = int(list_of_add_division_detail[0])

                        else:
                            add_division_is_valid = False

                        if(list_of_add_division_detail[1].isnumeric()):
                            list_of_add_division_detail[1] = int(list_of_add_division_detail[1])

                        else:
                            add_division_is_valid = False

                        list_of_add_division_detail[4] = list_of_add_division_detail[4].split()

                        temp_list = []
                        for detail in list_of_add_division_detail[4]:
                            if(detail.isnumeric()):
                                temp_list.append(int(detail))

                            else:
                                add_division_is_valid = False


                        list_of_add_division_detail[4] = temp_list

                        if(add_division_is_valid):
                            new_division_dictionary = {
                                "id": list_of_add_division_division[0],
                                "name": list_of_add_division_division[1],
                                "start_range": list_of_add_division_division[2],
                                "end_range": list_of_add_division_division[3],
                                "detail": [
                                    {
                                        "id": list_of_add_division_detail[0],
                                        "active_sheet": list_of_add_division_detail[1],
                                        "start_range": list_of_add_division_detail[2],
                                        "end_range": list_of_add_division_detail[3],
                                        "attribute": list_of_add_division_detail[4]
                                    }
                                ]
                            }

                            division_count = list_of_add_division_division[0] - 1
                            detail_count = 0

                            settings_data.append(new_division_dictionary)
                            settings_data = sorted(settings_data, key=itemgetter('id'))

                            Main.update(window, settings_data, division_attribute, detail_attribute, division_count, detail_count)

                            break

                        elif(not add_division_is_valid):
                            sg.Popup('Input Not Valid', keep_on_top=True)

                        
                add_division_window.close()
                window.Enable()

            elif(event == "delete_division_button"):
                del settings_data[division_count]
                division_count = len(settings_data) - 1
                detail_count = 0

                Main.update(window, settings_data, division_attribute, detail_attribute, division_count, detail_count)

            elif(event == "add_detail_button"):
                window.Disable()

                new_id = len(settings_data[division_count].get("detail")) + 1
                for detail_index, detail in enumerate(settings_data[division_count].get("detail")):
                    if(type(detail) == dict):
                        if(detail.get("id") != detail_index + 1):
                            new_id = detail_index + 1
                            break
                
                list_of_add_detail_input = [
                    new_id,
                    "Active Sheet",
                    "Start Range",
                    "End Range",
                    "Attribute"
                ]

                list_of_add_detail_button = [
                    "Cancel",
                    "Add"
                ]

                add_detail_layout = [
                    [sg.Text("Detail")],
                    [[sg.InputText(default_text=list_of_add_detail_input[attribute_index], size=(100, 1), key=f"add_detail_{attribute}", enable_events=True)] for attribute_index, attribute in enumerate(detail_attribute)],

                    [sg.Button(button, key=f"{button.lower()}_add_detail_button", enable_events=True, size=(12, 1)) for button in list_of_add_detail_button]
                ]

                add_detail_window = sg.Window("Add Detail", add_detail_layout, size=(245, 205), keep_on_top=True)

                while True:
                    add_detail_event, add_detail_values = add_detail_window.read()
                    if(add_detail_event in (sg.WINDOW_CLOSED, "cancel_add_detail_button")):
                        break

                    elif(add_detail_event == "add_add_detail_button"):
                        add_detail_is_valid = True

                        list_of_add_detail = []
                        for attribute in detail_attribute:
                            list_of_add_detail.append(add_detail_values[f"add_detail_{attribute}"])

                
                        if(list_of_add_detail[0].isnumeric()):
                            list_of_add_detail[0] = int(list_of_add_detail[0])

                        else:
                            add_detail_is_valid = False

                        if(list_of_add_detail[1].isnumeric()):
                            list_of_add_detail[1] = int(list_of_add_detail[1])

                        else:
                            add_detail_is_valid = False

                        list_of_add_detail[4] = list_of_add_detail[4].split()

                        temp_list = []
                        for detail in list_of_add_detail[4]:
                            if(detail.isnumeric()):
                                temp_list.append(int(detail))

                            else:
                                add_detail_is_valid = False


                        list_of_add_detail[4] = temp_list

                        if(add_detail_is_valid):
                            new_detail_dictionary = {
                                "id": list_of_add_detail[0],
                                "active_sheet": list_of_add_detail[1],
                                "start_range": list_of_add_detail[2],
                                "end_range": list_of_add_detail[3],
                                "attribute": list_of_add_detail[4]
                            }

                            detail_count = list_of_add_detail[0] - 1

                            settings_data[division_count].get("detail").append(new_detail_dictionary)

                            Main.update(window, settings_data, division_attribute, detail_attribute, division_count, detail_count)

                            break

                        elif(not add_detail_is_valid):
                            sg.Popup('Input Not Valid', keep_on_top=True)

                        
                add_detail_window.close()
                window.Enable()

            elif(event == "delete_detail_button"):
                del settings_data[division_count].get("detail")[detail_count]
                detail_count = len(settings_data[division_count].get("detail")) - 1

                Main.update(window, settings_data, division_attribute, detail_attribute, division_count, detail_count)

            if(detail_count == 0):
                window["previous_button"].update(disabled = True)

            elif(detail_count != 0):
                window["previous_button"].update(disabled = False)
            
            if(detail_count == len(settings_data[division_count].get("detail")) - 1):
                window["next_button"].update(disabled = True)

            elif(detail_count != len(settings_data[division_count].get("detail")) - 1):
                window["next_button"].update(disabled = False)
            
            if(len(settings_data) == 1):
                window["delete_division_button"].update(disabled = True)

            elif(len(settings_data) != 1):
                window["delete_division_button"].update(disabled = False)
            
            if(len(settings_data[division_count].get("detail")) == 1):
                window["delete_detail_button"].update(disabled = True)

            elif(len(settings_data[division_count].get("detail")) != 1):
                window["delete_detail_button"].update(disabled = False)

        window.close()

    
    def update(window, settings_data, division_attribute, detail_attribute, division_count, detail_count):
        window["listbox"].update([division.get("name") for division in settings_data])
        window["listbox"].update(set_to_index=[division_count], scroll_to_index=division_count)

        for attribute in division_attribute:
            window[f"division_{attribute}"].update(settings_data[division_count].get(attribute))


        for attribute in detail_attribute:
            window[f"detail_{attribute}"].update(settings_data[division_count].get("detail")[detail_count].get(attribute))


if(__name__ == "__main__"):
    Main.main()