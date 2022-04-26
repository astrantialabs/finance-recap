from hashlib import new
import os

class Main():
    def main():
        old_name = "./dist/update.exe" 
        new_name = "./dist/Update Data.exe" 

        os.rename(old_name, new_name)


if __name__ == "__main__":
    Main.main()