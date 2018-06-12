const chr = function (codePt) {
  if (codePt > 0xFFFF) {
    codePt -= 0x10000;
    return String.fromCharCode(0xD800 + (codePt >> 10), 0xDC00 + (codePt & 0x3FF));
  }
  return String.fromCharCode(codePt);
};
const ord = function (string) {
  var str = string + '',
      code = str.charCodeAt(0);

  if (code >= 0xD800 && code <= 0xDBFF) {
    var hi = code
    if (str.length === 1) {
      return code;
    }
    var low = str.charCodeAt(1);
    return ((hi - 0xD800) * 0x400) + (low - 0xDC00) + 0x10000;
  }
  return code;
};

class teacrypt {
  static saltGenerator (n = 5){
    var r = "";
    for(let i = 0; i < n; i++){
      r += chr(Math.floor(Math.random() * (0x7f)));
    }
    return r;
  };
  static encrypt (string, key, binarySafe = 1){
      var slen = string.length, klen = key.length, r = "", newKey = "", salt = teacrypt.saltGenerator(), cost = 1;
      for(let i = 0, j = 0; i < klen; i++){ 
          newKey += chr(ord(key[i]) ^ ord(salt[j++]));
          if(j === 5){
              j = 0;
          }
      }
      for(let i = 0, j = 0, k = 0; i < slen; i++){
          r += chr(
                ord(string[i]) ^ ord(newKey[j++]) ^ ord(salt[k++]) ^ 
                (i << j) ^ (k >> j) ^ (slen % cost) ^ (cost >> j) ^ (cost >> i) ^ (cost >> k) ^
                (cost ^ ( slen % (i + j + k + 1))) ^ ((cost << i) % 2) ^ ((cost << j) % 2) ^
                ((cost << k) % 2) ^ ((cost * (i + j + k)) % 3)
              );
          cost++;
          if(j === klen){
              j = 0;
          }
          if(k === 5){
              k = 0; 
          }
      }
      r += salt;
      if(binarySafe){
          return Buffer.from(r).toString('base64').split("").reverse().join("");
      } else {
          return r;
      }
  }

  static decrypt (string, key, binarySafe = 1){
      if(binarySafe){
        string = Buffer.from(string.split("").reverse().join(""), 'base64').toString().split("");
      }
      string = string.join("");
      var slen = string.length,
          salt = string.substr(slen - 5),
          string = string.substr(0, slen = slen - 5),
          klen = key.length,
          newKey = "",
          r = "",
          cost = 1;
      for(let i = 0, j = 0; i < klen; i++){ 
          newKey += chr(ord(key[i]) ^ ord(salt[j++]));
              if(j === 5){
                  j = 0;
              }
      }
      for(let i = 0,j = 0, k = 0; i < slen; i++){
          r += chr(
                ord(string[i]) ^ ord(newKey[j++]) ^ ord(salt[k++]) ^ 
                (i << j) ^ (k >> j) ^ (slen % cost) ^ (cost >> j) ^ (cost >> i) ^ (cost >> k) ^
                (cost ^ ( slen % (i + j + k + 1))) ^ ((cost << i) % 2) ^ ((cost << j) % 2) ^
                ((cost << k) % 2) ^ ((cost * (i + j + k)) % 3)
              );
          cost++;
          if(j === klen){
              j = 0;
          }
          if(k === 5){
              k = 0; 
          }
      }
      return r;
  }
}

var argv = process.argv;
if (argv[2] === "encrypt") {
  console.log(teacrypt.encrypt(argv[3], argv[4], true));
} else if (argv[2] === "decrypt") {
  console.log(teacrypt.decrypt(argv[3], argv[4], true));
}
