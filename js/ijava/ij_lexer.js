function ij_word_t(line, type, text) {
  this.line = line;
  this.type = type;
  this.text = text;
}

function ij_alpha_char(chr) {
  return ((chr >= "a" && chr <= "z") || (chr >= "A" && chr <= "Z"));
}

function ij_digit_char(chr) {
  return (chr >= "0" && chr <= "9");
}

function ij_start_char(chr) {
  return (chr === "_" || (chr >= "a" && chr <= "z") || (chr >= "A" && chr <= "Z"));
}

function ij_name_char(chr) {
  return (chr === "_" || (chr >= "a" && chr <= "z") || (chr >= "A" && chr <= "Z") || (chr >= "0" && chr <= "9"));
}

function ij_alnum_char(chr) {
  return ((chr >= "a" && chr <= "z") || (chr >= "A" && chr <= "Z") || (chr >= "0" && chr <= "9"));
}

function ij_space_char(chr) {
  return (chr === " " || chr === "\t" || chr === "\n");
}

function ij_type_of(word) {
  if (word.length === 0) return null;
  
  if (word[0] === "\"") {
    if (word.length >= 2 && word[word.length - 1] === "\"") {
      if (word.length >= 3 && word[word.length - 2] === "\\") {
        return null;
      }
      
      return "(string)";
    }
    
    return null;
  }
  
  if (word[0] === "'") {
    if (word.length >= 2 && word[word.length - 1] === "'") {
      if (word.length >= 3 && word[word.length - 2] === "\\") {
        return null;
      }
      
      return "(char)";
    }
    
    return null;
  }
  
  if (word === "int") return "int";
  else if (word === "char") return "char";
  else if (word === "double") return "double";
  else if (word === "boolean") return "boolean";
  else if (word === "String") return "String";
  else if (word === "void") return "void";
  
  if (word == "final") return "final";
  else if (word == "class") return "class";
  
  if (word == "new") return "new";
  
  if (word == "if") return "if";
  else if (word == "else") return "else";
  else if (word == "while") return "while";
  else if (word == "for") return "for";
  else if (word == "switch") return "switch";
  else if (word == "case") return "case";
  
  if (word == "break") return "break";
  else if (word == "continue") return "continue";
  else if (word == "return") return "return";
  
  if (word === "(") return "(";
  else if (word === ")") return ")";
  else if (word === "{") return "{";
  else if (word === "}") return "}";
  else if (word === "[") return "[";
  else if (word === "]") return "]";
  else if (word === ",") return ",";
  else if (word === ";") return ";";
  else if (word === ".") return ".";
  
  if (word === "?") return "?";
  else if (word === ":") return ":";
  
  if (word === "+") return "(operator 3)";
  else if (word === "-") return "(operator 3)";
  else if (word === "*") return "(operator 2)";
  else if (word === "/") return "(operator 2)";
  else if (word === "%") return "(operator 2)";
  else if (word === "&") return "(operator 4)";
  else if (word === "|") return "(operator 4)";
  else if (word === "^") return "(operator 4)";
  else if (word === "**") return "(operator 1)";
  else if (word === "<<") return "(operator 1)";
  else if (word === ">>") return "(operator 1)";
  else if (word === "~") return "~";
  else if (word === "!") return "!";
  else if (word === "==") return "(operator 5)";
  else if (word === "!=") return "(operator 5)";
  else if (word === ">") return "(operator 5)";
  else if (word === "<=") return "(operator 5)";
  else if (word === "<") return "(operator 5)";
  else if (word === ">=") return "(operator 5)";
  else if (word === "&&") return "(operator 6)";
  else if (word === "||") return "(operator 6)";
  
  if (word === "=") return "=";
  else if (word === "+=") return "=";
  else if (word === "-=") return "=";
  else if (word === "*=") return "=";
  else if (word === "/=") return "=";
  else if (word === "%=") return "=";
  else if (word === "&=") return "=";
  else if (word === "|=") return "=";
  else if (word === "^=") return "=";
  else if (word === "**=") return "=";
  else if (word === "<<=") return "=";
  else if (word === ">>=") return "=";
  else if (word === "&&=") return "=";
  else if (word === "||=") return "=";
  
  if (word === "++") return "++";
  else if (word === "--") return "--";
  
  let is_number = true;
  let is_name = true;
  
  let dot_count = 0;
  
  for (let index = 0; index < word.length; index++) {
    if (index > 0) {
      if (!ij_name_char(word[index])) {
        is_name = false;
      }
    } else {
      if (!ij_start_char(word[index])) {
        is_name = false;
      }
    }
    
    if (word[index] === ".") {
      dot_count++;
      
      if (dot_count > 1) {
        is_number = false;
      }
    }
    
    if (index === 0 && !ij_digit_char(word[index])) {
      is_number = false;
    } else if (word[index] !== "." && !ij_alnum_char(word[index])) {
      is_number = false;
    }
    
    if (!is_name && !is_number) return null;
  }
  
  if (is_name) return "(name)";
  else if (is_number) return "(number)";
  
  return null;
}

function ij_lexer(code) {
  let words = [];
  let word = "";
  
  let in_single = false;
  let in_multi = false;
  
  let in_string = false;
  let line = 1;
  
  let prev = null;
  
  for (let i = 0; i < code.length; i++) {
    let chr = code[i];
    let type = ij_type_of(word + chr);
    
    if (!in_string && i < code.length - 2) {
      if (chr === "/" && code[i + 1] === "/") {
        in_single = true;
      }
      
      if (chr === "/" && code[i + 1] === "*") {
        in_multi = true;
      }
    }
    
    if (!in_single && !in_multi) {
      if (word.length === 0 && (chr === "\"" || chr === "'")) in_string = true;
      
      if (in_string) {
        if (word.length >= 1 && type !== null) in_string = false;
        
        word += chr;
        prev = type;
      } else {
        if (prev !== null && type === null) {
          words.push(new ij_word_t(line, prev, word));
          word = "";
          
          type = ij_type_of(chr);
          prev = null;
        }
        
        if (!ij_space_char(chr)) {
          if (chr === "\"" || chr === "'") in_string = true;
          
          word += chr;
          prev = type;
        }
      }
    }
    
    if (!in_string && i > 0) {
      if (chr === "/" && code[i - 1] === "*") {
        in_multi = false;
      }
    }
    
    if (chr === "\n") {
      in_single = false;
      line++;
    }
  }
  
  if (prev !== null) {
    words.push(new ij_word_t(line, prev, word));
  }
  
  words.push(new ij_word_t(line, "(eof)", "(eof)"));
  return words;
}
