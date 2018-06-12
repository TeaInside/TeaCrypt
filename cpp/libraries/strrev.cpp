
#include "strrev.h"
#include <iostream>

std::string strrev(std::string line) 
{
  std::string::reverse_iterator it;
  std::string result = "";
  for (it = line.rbegin(); it < line.rend(); it++) 
  {
    result += *it;
  }
  return result;
}
