import os
import os.path

class Main():
    def main():
        old_name = "./dist/update.exe" 
        new_name = "./dist/Update Data.exe" 

        if(os.path.exists(new_name)):
            os.remove(new_name)

        if(os.path.exists(old_name)):
            os.rename(old_name, new_name)


if __name__ == "__main__":
    Main.main()