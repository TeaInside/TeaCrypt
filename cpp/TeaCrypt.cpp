
/**
 * @author Rizkie Yudha Pratama <personal@rizkiepratama.net>
 * @link https://github.com/VikTymZ
 * @license MIT
 * @package TeaCrypt
 */

#include <string>
#include <iostream>
#include "TeaCrypt.h"
#include "libraries/base64.h"
#include "libraries/strrev.h"

std::string TeaCrypt::saltGenerator(int n = 5)
{
  srand(time(0));
  std::string salt;
  for(auto i = 0; i < n; i++) {
    salt += (char) rand() % 255;
  }
  return salt;
}


/**
 * Tea Encrypt
 *
 * Encryption Method
 */
std::string TeaCrypt::Encrypt(std::string data, std::string key, bool isBinarySafe = true)
{
  std::string encrypted, newKey, salt = saltGenerator();
  int cost = 1;

  // Generate new Key
  for(auto it = key.begin(), jt = salt.begin(); it != key.end(); it++ ) {
    newKey += (char) (((int) *it) ^ ((int) *jt++));
    if(jt == salt.end()) {
      jt = salt.begin();
    }
  }

  // Encrypt Our Data
  
  for(int it = 0, jt = 0, kt = 0; it < data.length(); it++) {
      // auto 
      //   iit = std::distance(data.begin(), it), 
      // ijt = std::distance(salt.begin(), jt),
      // ikt = std::distance(newKey.begin(), kt);
    // std::cout << it << " ";
    // std::cout << jt << " ";
    encrypted += (char) (
      (data[it]) ^ (newKey[jt++]) ^ (salt[kt++]) ^ (it << jt) ^ (kt >> jt) ^
      (data.length() % cost) ^ (cost >> jt) ^ (cost >> it) ^ (cost >> kt) ^ 
      (cost ^ (data.length() % (it + jt + kt + 1))) ^
      ((cost << it) % 2) ^ ((cost << jt) % 2) ^ ((cost << kt) % 2) ^
      ((cost * (it + jt + kt)) % 3)
    );
    cost++;

    if(jt == newKey.length()) {
      jt = 0;
    }

    if(kt == 5) {
      kt = 0;
    }
  }
  encrypted += salt;
  if(isBinarySafe) {
    unsigned char *t = (unsigned char *) encrypted.c_str();
    return strrev(base64_encode(t, encrypted.length()));
  } else {
    return encrypted;
  }
}



/**
 * Tea Encrypt
 *
 * Decrypt Method
 */
std::string TeaCrypt::Decrypt(std::string data, std::string key, bool isBinarySafe = true)
{
  if (isBinarySafe)
  {
    data = base64_decode(strrev(data));
  }

  std::string decrypted, newKey, salt = data.substr(data.length() - 5, data.length());
  int cost = 1;
  data = data.substr(0, data.length() - 5);

  for(auto it = key.begin(), jt = salt.begin(); it != key.end(); it++ ) {
    newKey += (char) (((int) *it) ^ ((int) *jt++));

    if( jt == salt.end() ) {
      jt = salt.begin();
    }
  }

  for(int it = 0, jt = 0, kt = 0; it < data.length(); it++) {
    // auto iit = std::distance(data.begin(), it),
    //   ijt = std::distance(salt.begin(), jt),
    //   ikt = std::distance(newKey.begin(), kt);
    // decrypted += (char) (
    // ((int) *it) ^ ((int) *jt++) ^ ((int) *kt++) ^ (iit << ijt) ^ (ikt >> ijt) ^
    // (data.length() % cost) ^ (cost >> ijt) ^ (cost >> iit) ^ (cost >> ikt) ^ 
    // (cost ^ (data.length() % (iit + ijt + ikt + 1))) ^
    // ((cost << iit) % 2) ^ ((cost << ijt) % 2) ^ ((cost << ikt) % 2) ^
    // ((cost * (iit + ijt + ikt)) % 3)
    // );
    decrypted += (char) (
      (data[it]) ^ (newKey[jt++]) ^ (salt[kt++]) ^ (it << jt) ^ (kt >> jt) ^
      (data.length() % cost) ^ (cost >> jt) ^ (cost >> it) ^ (cost >> kt) ^ 
      (cost ^ (data.length() % (it + jt + kt + 1))) ^
      ((cost << it) % 2) ^ ((cost << jt) % 2) ^ ((cost << kt) % 2) ^
      ((cost * (it + jt + kt)) % 3)
    );
    cost++;

    if(jt == newKey.length()) {
      jt = 0;
    }

    if(kt == 5) {
      kt = 0;
    }
  }

  return decrypted;
}
