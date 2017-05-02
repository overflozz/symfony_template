#!C:/Users/Valentin/Miniconda3/python.exe
import sys
import time

def serialize(tableau):
    string  = ""
    string += "a:"
    string += str(len(tableau))
    string +=  ":{"
    for i in range(len(tableau)):
        if(isinstance(tableau[i], int) == True):
            string+="i:" + str(i) +";" + "i:" + str(tableau[i]) + ";"
        elif(isinstance(tableau[i], str)):
            string+="i:" + str(i) +";s:"+str(len(tableau[i]))+":\"" + str(tableau[i]) + "\";"
            
            
    string += "}"
    return string
    
arg1 = sys.argv[1]
arg2 = sys.argv[2]
time.sleep(10)

tableau=[arg1, arg2]

#on serialize comme php pourrait le faire :

print (serialize(tableau))
print (tableau)
