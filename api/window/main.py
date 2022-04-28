import os

class Main():
    def main():
        os.chdir("./api/window")
        os.popen('compile.sh')


if __name__ == "__main__":
    Main.main()