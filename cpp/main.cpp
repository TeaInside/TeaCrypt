
#include <iostream>
#include "TeaCrypt.h"

int main(int argc, char* argv[]) {

	std::string method = argv[1];
	std::string data   = argv[2];
	std::string key    = argv[3];

	if (method == "encrypt")
	{
		std::cout << TeaCrypt::Encrypt(data, key, true);
	} else if (method == "decrypt") {
		std::cout << TeaCrypt::Decrypt(data, key, true);
	}
	
	// std::cout << "What Should We Encrypt?\n -> ";
	// std::getline(std::cin, data_input);
	// auto data = TeaCrypt::Encrypt(argv[1], "Some Key", true);
	// std::cout << "\nLet\'s Encrypt, Result Was: " << data << "\n";
	// std::cout << "Let\'s Decrypt It Back, Result Was: " << TeaCrypt::Decrypt(data, "Some Key", true) << "\n";

	return 0;
}
