CC = g++ 
main.o:
	$(CC) -c main.cpp
base64.o:
	$(CC) -c libraries/base64.cpp
strrev.o:
	$(CC) -c libraries/strrev.cpp
TeaCrypt.o:
	$(CC) -c TeaCrypt.cpp 
bin:
	$(CC) *.o -o bin
clean: 
	rm -f core *.o 