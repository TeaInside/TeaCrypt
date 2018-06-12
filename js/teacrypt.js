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
      var r = "", newKey = "",
          cost = 1,
          // salt = teacrypt.saltGenerator(),
          salt = "12345",
          key = key + salt;
      
      for(let i=0,j=0,k=0; i < string.length; i++){
          r += chr(
                ord(string[i]) ^ ord(key[j]) ^ ord(salt[k]) ^
                (((j + 2) << 2) ^ ((key.length % (i + 1)) << 2))
              );
          
          j++; k++;

          if ((j % 2) === 0) {
            cost++;
          }

          if (j === key.length) {
            j = 0;
          }

          if (k === 5) {
            k = 0;
          }
      }
      r += salt;
      // console.log(r);process.exit();
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
          key = key + salt,
          newKey = "", r = "",
          cost = 1;
      for(let i=0,j=0,k=0; i < string.length; i++){
          r += chr(
                ord(string[i]) ^ ord(key[j]) ^ ord(salt[k]) ^
                /*(((j + 2) << 2)) ^*/ /*((key.length % (i + 1)) << 2)*/
                (key.length % (i + 1))
              );
          
          j++; k++;

          if ((j % 2) === 0) {
            cost++;
          }

          if (j === key.length) {
            j = 0;
          }

          if (k === 5) {
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
