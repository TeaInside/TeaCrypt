

#ifndef TEACRYPT_H_2F8CAD2A44D50DB65D8F5B09BFA244FB6FBA35BC
#define TEACRYPT_H_2F8CAD2A44D50DB65D8F5B09BFA244FB6FBA35BC


class TeaCrypt
{
  public:
  	static std::string saltGenerator(int);
  	static std::string Encrypt(std::string data, std::string key, bool isBinarySafe);
  	static std::string Decrypt(std::string data, std::string key, bool isBinarySafe);
};

#endif
