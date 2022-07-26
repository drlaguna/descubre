let ij_words = null;
let ij_index = 0;

let ij_functions = {};
let ij_classes = {};

let ij_contexts = [{}];

let ij_current_class = null;
let ij_current_level = null;

let ij_return_type = null;
let ij_complain = true;

let ij_ran_funcs = {};

function ij_mangle(name) {
  return "__ij_" + name;
}

function ij_mangle_function(name, array) {
  let text = "";
  
  for (let type of array) {
    text += type.replace(/\[\]/g, "_A") + "_";
  }
  
  text += "_";
  return ij_mangle(text + name);
}

function ij_is_array(type) {
  for (let chr of type) {
    if (chr === "{") break;
    if (chr === "[") return true;
  }
  
  return false;
}

function ij_is_dict(type) {
  for (let chr of type) {
    if (chr === "[") break;
    if (chr === "{") return true;
  }
  
  return false;
}

function ij_similar_type(type_1, type_2) {
  if (type_1 === type_2) return true;
  if (type_1 === "int" && type_2 === "double") return true;
  if (type_1 === "null") return true;
  
  if (!ij_complain) return true;
  return false;
}

function ij_cast(code, old_type, new_type) {
  if (old_type === new_type) return code;
  else if (old_type === "char" && (new_type === "int" || new_type === "double")) return "(" + code + ").charCodeAt(0)";
  else if (new_type === "String") return "(" + code + ").toString()";
  else if (new_type === "int") return "parseInt(" + code + ")";
  else if (new_type === "double") return "parseFloat(" + code + ")";
  else if (new_type === "char") return "(" + code + ").toString().charAt(0)";
  else if (old_type === "null") return code;
  
  if (!ij_complain) return code;
  return null;
}

function ij_result_type(operator, type_1, type_2) {
  if (operator === "+" && (type_1 === "String" || type_2 === "String")) {
    return "String";
  } else if (operator === "==" || operator === "!=") {
    if (type_1 === "null" || type_2 === "null") return "boolean";
    else if ((type_1 === "int" || type_1 === "double") && (type_2 === "int" || type_2 === "double")) return "boolean";
    else if (type_1 === type_2) return "boolean";
  } else if (operator === ">" || operator === "<=" || operator === "<" || operator === ">=") {
    if ((type_1 === "int" || type_1 === "double") && (type_2 === "int" || type_2 === "double")) return "boolean";
  } else if (operator === "&&" || operator === "||") {
    if (type_1 === "boolean" && type_2 === "boolean") return "boolean";
  } else if ((type_1 === "int" || type_1 === "double") && (type_2 === "int" || type_2 === "double")) {
    if (type_1 === "double" || type_2 === "double") return "double";
    else return "int";
  }
  
  if (!ij_complain) return (type_1 === "null" ? type_2 : type_1);
  return null;
}

function ij_expect(type) {
  if (ij_index >= ij_words.length) return null;
  if (ij_words[ij_index].type !== type) return null;
  
  return ij_words[ij_index++];
}

function ij_throw(error) {
  error = error.replace(/TEXT/g, ij_words[ij_index].text);
  error = error.replace(/TYPE/g, ij_words[ij_index].type);
  // error = "Line " + ij_words[ij_index].line + ": " + error;
  
  throw [ij_words[ij_index].line, error];
}

function ij_assert(cond, error) {
  if (cond) ij_throw(error);
}

function ij_assert_exist(cond, error) {
  if (ij_complain && cond) ij_throw(error);
}

function ij_get_variable(name) {
  for (let i = ij_contexts.length - 1; i >= 0; i--) {
    if (name in ij_contexts[i]) {
      return {
        type: ij_contexts[i][name].type,
        can_edit: ij_contexts[i][name].can_edit,
        is_class: (i === ij_current_level && ij_current_class !== null),
      };
    }
  }
  
  return null;
}

function ij_parse_simple_type() {
  let type = "";
  
  if (ij_expect("int") !== null) {
    type += "int";
  } else if (ij_expect("char") !== null) {
    type += "char";
  } else if (ij_expect("double") !== null) {
    type += "double";
  } else if (ij_expect("boolean") !== null) {
    type += "boolean";
  } else if (ij_expect("String") !== null) {
    type += "String";
  } else if (ij_expect("void") !== null) {
    type += "void";
  } else if (ij_mangle(ij_words[ij_index].text) in ij_classes) {
    type += ij_words[ij_index++].text;
  } else {
    ij_assert(true, "Invalid type 'TEXT'.");
  }
  
  return type;
}

function ij_parse_type() {
  let type = ij_parse_simple_type();
  
  while (true) {
    if (ij_expect("[") !== null) {
      type += "[]";
      ij_assert(ij_expect("]") === null, "Expected a matching closing bracket, got 'TEXT'.");
    } else if (ij_expect("{") !== null) {
      type += "{}";
      ij_assert(ij_expect("}") === null, "Expected a matching closing brace, got 'TEXT'.");
    } else {
      break;
    }
  }
  
  return type;
}

function ij_parse_array(type) {
  if (ij_expect("{") === null, "Expected an opening brace, got 'TEXT'.");
  let output = "[";
  
  let new_type = type.slice(0, -2);
  
  while (true) {
    if (ij_words[ij_index].type === "{") {
      ij_assert(new_type.indexOf("[") < 0, "Expected a value of type '" + new_type + "', got an array literal.");
      output += ij_parse_array(new_type).output;
    } else {
      let value = ij_parse_expression();
      ij_assert(!ij_similar_type(value.type, new_type), "Cannot convert type '" + value.type + "' to '" + new_type + "'.");
      
      output += value.output;
    }
    
    if (ij_expect("}") !== null) break;
    ij_assert(ij_expect(",") === null, "Expected a comma or a matching closing brace in array literal, got 'TEXT'.");
    
    if (ij_expect("}") !== null) break; // some psychos like me tend to place commas before closing array literals
    output += ", ";
  }
  
  output += "]";
  return {type: type, output: output, left: false};
}

function ij_parse_expression_0() {
  let word = ij_words[ij_index++];
  
  if (word.type === "(string)") {
    return {type: "String", output: word.text, left: false};
  } else if (word.type === "(char)") {
    return {type: "char", output: word.text, left: false};
  } else if (word.type === "(number)") {
    let type = "int";
    if (word.text.indexOf(".") >= 0) type = "double";
    
    return {type: type, output: word.text, left: false};
  } else if (word.type == "new") {
    let output = "";
    let type = ij_parse_simple_type();
    
    if (ij_expect("(") !== null) {
      ij_assert_exist(!(ij_mangle(type) in ij_classes), "'" + type + "' does not exist as a class.");
      let array = [type];
      
      if (ij_expect(")") === null) {
        while (true) {
          let value = ij_parse_expression();
          
          array.push(value.type);
          output += value.output;
          
          if (ij_expect(")") !== null) {
            break;
          }
          
          output += ", ";
          ij_assert(ij_expect(",") === null, "Expected a comma or a closing parenthesis in function call, got 'TEXT'.");
        }
      }
      
      ij_assert(!(ij_mangle(type) in ij_classes[ij_mangle(type)].methods), "Class '" + type + "' has no constructors.");
      
      for (let args of ij_classes[ij_mangle(type)].methods[ij_mangle(type)]) {
        let valid = true;
        
        for (let index in array) {
          if (!ij_similar_type(array[index], args[index])) {
            valid = false;
            break;
          }
        }
        
        if (valid) {
          output = "new " + ij_mangle(type) + "(\"" + ij_mangle_function(type, args) + "\", [" + output + "])";
          return {type: type, output: output, left: false};
        }
      }
      
      ij_assert(true, "Expected a different amount of arguments for '" + type + "''s constructor, got " + (array.length - 1) + ".");
      return {type: "null", output: "", left: false};
    } else if (ij_words[ij_index].type === "[") {
      let sizes = "";
      
      while (ij_expect("[") !== null) {
        let value = ij_parse_expression();
        ij_assert(value.type !== "int", "Array sizes must be integers, not '" + value.type + "'.");
        
        sizes += value.output;
        type += "[]";
        
        ij_assert(ij_expect("]") === null, "Expected a matching closing bracket, got 'TEXT'.");
        if (ij_words[ij_index].type === "[") sizes += ", ";
      }
      
      let default_value = "undefined";
      
      if (type.replace(/\[\]/g, "") === "int" || type.replace(/\[\]/g, "") === "double") {
        default_value = "0";
      } else if (type.replace(/\[\]/g, "") === "boolean") {
        default_value = "false";
      }
      
      return {type: type, output: "m([" + sizes + "], " + default_value + ")", left: false};
    } else if (ij_words[ij_index].type === "{") {
      ij_index++;
      ij_assert(ij_expect("}") === null, "Expected a matching closing brace, got 'TEXT'.");
      
      return {type: type + "{}", output: "{}", left: false};
    } else {
      ij_assert(true, "Expected '(', '{' or '[', got 'TEXT'.");
      return {type: "null", output: "", left: false};
    }
  } else if (word.type === "(name)") {
    if (ij_expect("(") !== null) {
      let in_class = false;
      
      if (ij_current_class !== null && ij_mangle(word.text) in ij_classes[ij_mangle(ij_current_class)].methods) {
        in_class = true;
      } else {
        ij_assert(!(word.text in ij_functions), "'" + word.text + "' does not exist as a function.");
      }
      
      let output = "";
      let array = ["null"];
      
      if (ij_expect(")") === null) {
        while (true) {
          let value = ij_parse_expression();
          
          array.push(value.type);
          output += value.output;
          
          if (ij_expect(")") !== null) {
            break;
          }
          
          output += ", ";
          ij_assert(ij_expect(",") === null, "Expected a comma or a closing parenthesis in function call, got 'TEXT'.");
        }
      }
      
      if (in_class) {
        for (let args of ij_classes[ij_mangle(ij_current_class)].methods[ij_mangle(word.text)]) {
          array[0] = args[0];
          let valid = true;
          
          for (let index in array) {
            if (!ij_similar_type(array[index], args[index])) {
              valid = false;
              break;
            }
          }
          
          if (valid) {
            output = "this." + ij_mangle_function(word.text, args) + "(" + output + ")";
            return {type: array[0], output: output, left: false};
          }
        }
      } else {
        for (let args of ij_functions[word.text]) {
          array[0] = args[0];
          let valid = true;
          
          for (let index in array) {
            if (!ij_similar_type(array[index], args[index])) {
              valid = false;
              break;
            }
          }
          
          if (valid) {
            output = ij_mangle_function(word.text, args) + "(" + output + ")";
            ij_ran_funcs[word.text] = word.text;
            
            return {type: array[0], output: output, left: false};
          }
        }
      }
      
      ij_assert(true, "Expected a different amount of arguments for '" + word.text + "', got " + (array.length - 1) + ".");
      return {type: "null", output: "", left: false};
    }
    
    let data = ij_get_variable(word.text);
    
    if (data !== null) {
      let type = data.type;
      let output = "";
      
      if (word.text !== "this" && data.is_class) output += "this.";
      
      if (word.text === "this" && ij_current_class !== null) output += "this";
      else output += ij_mangle(word.text);
      
      return {type: type, output: output, left: data.can_edit};
    }
    
    if (word.text in ij_functions) {
      let output = "";
      output += ij_mangle_function(word.text, ij_functions[word.text][0]);
      
      return {type: "function", output: output, left: false};
    }
    
    ij_assert_exist(data === null, "'" + word.text + "' does not exist as a variable.");
    return {type: "null", output: "", left: false};
  } else if (word.type == "(") {
    let old_index = ij_index;
    
    let found_type = true;
    let type;
    
    try {
      type = ij_parse_type();
    } catch (error) {
      ij_index = old_index;
      found_type = false;
    }
    
    if (found_type) {
      ij_assert(ij_expect(")") === null, "Expected a matching closing parenthesis, got 'TEXT'.");
      let value = ij_parse_expression_0();
      
      let result = ij_cast(value.output, value.type, type);
      ij_assert(result === null, "Cannot convert type '" + value.type + "' to '" + type + "'.");
      
      return {type: type, output: result, left: false};
    } else {
      let value = ij_parse_expression();
      ij_assert(ij_expect(")") === null, "Expected a matching closing parenthesis, got 'TEXT'.");
      
      return {type: value.type, output: "(" + value.output + ")", left: value.left};
    }
  }
  
  return {type: "null", output: "", left: false};
}

function ij_parse_expression_1() {
  let value = ij_parse_expression_0();
  
  while (true) {
    let current_line = ij_words[ij_index].line;
    
    if (ij_expect("[") !== null) {
      if (ij_is_array(value.type)) {
        let index = ij_parse_expression();
        ij_assert(ij_expect("]") === null, "Expected a matching closing bracket, got 'TEXT'.");
        
        ij_assert(index.type !== "int", "Array indexes must be integers, not '" + index.type + "'.");
        
        let output = "(" + value.output + ")[" + index.output + "]";
        
        // if (!value.left) {
        //   output = "(function() {let array = " + value.output + "; let index = parseInt(" + index.output + "); ";
        //   output += "if (!isNaN(index) && index >= 0 && index < array.length) return array[index]; ";
        //   output += "else throw [" + current_line + ", \"Cannot get out of bounds index \" + index + \".\"];})()";
        // }
        
        value = {type: value.type.replace("[]", ""), output: output, left: value.left};
      } else if (ij_is_dict(value.type)) {
        let index = ij_parse_expression();
        ij_assert(ij_expect("]") === null, "Expected a matching closing bracket, got 'TEXT'.");
        
        ij_assert(index.type !== "String", "Dictionary indexes must be strings, not '" + index.type + "'.");
        
        let output = "(" + value.output + ")[" + index.output + "]";
        
        // if (!value.left) {
        //   output = "(function() {let dict = " + value.output + "; let index = " + index.output + "; if (index in dict) return dict[index]; ";
        //   output += "else throw [" + current_line + ", \"Cannot get out of bounds index \" + index + \".\"];})()";
        // }
        
        value = {type: value.type.replace("{}", ""), output: output, left: value.left};
      } else {
        ij_assert(true, "Cannot index a value of type '" + value.type + "'.");
      }
      
    } else if (ij_expect(".") !== null) {
      let name = ij_expect("(name)");
      ij_assert(name === null, "'" + name.text + "' is not a valid property or method name.");
      
      if (ij_expect("(") !== null) {
        if (value.type === "String") {
          if (name.text === "length") {
            ij_assert(ij_expect(")") === null, "Expected a closing parenthesis in function call, got 'TEXT'.");
            return {type: "int", output: value.output + ".length", left: false};
          } else if (name.text === "charAt") {
            let index = ij_parse_expression();
            ij_assert(index.type !== "int", "Expected an integer, got a value of type '" + index.type + "'.");
            
            ij_assert(ij_expect(")") === null, "Expected a closing parenthesis in function call, got 'TEXT'.");
            return {type: "char", output: value.output + "." + name.text + "(" + index.output + ")", left: false};
          } else if (name.text === "concat") {
            let index = ij_parse_expression();
            ij_assert(index.type !== "String", "Expected a String, got a value of type '" + index.type + "'.");
            
            ij_assert(ij_expect(")") === null, "Expected a closing parenthesis in function call, got 'TEXT'.");
            return {type: "String", output: value.output + "." + name.text + "(" + index.output + ")", left: false};
          } else if (name.text === "substring") {
            let index = ij_parse_expression();
            ij_assert(index.type !== "int", "Expected an integer, got a value of type '" + index.type + "'.");
            
            let length = undefined;
            
            if (ij_expect(",") !== null) {
              length = ij_parse_expression();
              ij_assert(length.type !== "int", "Expected an integer, got a value of type '" + length.type + "'.");
            }
            
            ij_assert(ij_expect(")") === null, "Expected a closing parenthesis in function call, got 'TEXT'.");
            
            if (length === undefined) {
              return {type: "String", output: value.output + "." + name.text + "(" + index.output + ")", left: false};
            } else {
              return {type: "String", output: value.output + "." + name.text + "(" + index.output + ", " + length.output + ")", left: false};
            }
          }
        }
        
        ij_assert(!(ij_mangle(value.type) in ij_classes), "Type '" + value.type + "' is not a class.");
        
        ij_assert(!(ij_mangle(name.text) in ij_classes[ij_mangle(value.type)].methods), "Class '" + value.type + "' does not have a method called '" + name.text + "'.");
        let output = "";
        
        let array = ["null"];
        
        if (ij_expect(")") === null) {
          while (true) {
            let value = ij_parse_expression();
            
            array.push(value.type);
            output += value.output;
            
            if (ij_expect(")") !== null) {
              break;
            }
            
            output += ", ";
            ij_assert(ij_expect(",") === null, "Expected a comma or a closing parenthesis in function call, got 'TEXT'.");
          }
        }
        
        let done = false;
        
        for (let args of ij_classes[ij_mangle(value.type)].methods[ij_mangle(name.text)]) {
          array[0] = args[0];
          let valid = true;
          
          for (let index in array) {
            if (!ij_similar_type(array[index], args[index])) {
              valid = false;
              break;
            }
          }
          
          if (valid) {
            output = value.output + "." + ij_mangle_function(name.text, args) + "(" + output + ")";
            value = {type: array[0], output: output, left: false};
            
            done = true;
            break;
          }
        }
        
        if (!done) {
          ij_assert(true, "Expected a different amount of arguments for '" + name.text + "', got " + (array.length - 1) + ".");
          value = {type: "null", output: "", left: false};
        }
      } else {
        if (value.type.indexOf("[") >= 0 && name.text === "length") {
          value = {type: "int", output: value.output + ".length", left: false};
        } else {
          ij_assert(!(ij_mangle(value.type) in ij_classes), "Type '" + value.type + "' is not a class.");
          
          ij_assert(!(ij_mangle(name.text) in ij_classes[ij_mangle(value.type)].properties), "Class '" + value.type + "' does not have a property called '" + name.text + "'.");
          value = {type: ij_classes[ij_mangle(value.type)].properties[ij_mangle(name.text)].type, output: value.output + "." + ij_mangle(name.text), left: value.left};
        }
      }
    } else {
      break;
    }
  }
  
  return value;
}

function ij_parse_expression_2() {
  if (ij_words[ij_index].text === "-") {
    ij_index++;
    
    let value = ij_parse_expression_2();
    ij_assert(ij_result_type("*", value.type, "int") === null, "Cannot apply the '(-)' operator to the type '" + value.type + "'.");
    
    return {type: value.type, output: "-" + value.output, left: false};
  }
  
  if (ij_expect("!") !== null) {
    let value = ij_parse_expression_2();
    ij_assert(value.type !== "boolean", "Cannot apply the '!' operator to the type '" + value.type + "'.");
    
    return {type: value.type, output: "!" + value.output, left: false};
  }
  
  if (ij_expect("~") !== null) {
    let value = ij_parse_expression_2();
    ij_assert(ij_result_type("*", value.type, "int") === null, "Cannot apply the '~' operator to the type '" + value.type + "'.");
    
    return {type: value.type, output: "-(" + value.output + " + 1)", left: false};
  }
  
  if (ij_expect("++") !== null) {
    let value = ij_parse_expression_2();
    
    ij_assert(ij_result_type("+", value.type, "int") === null, "Cannot apply the '++' operator to the type '" + value.type + "'.");
    ij_assert(!value.left, "Cannot apply the '++' operator to a non-lvalue.");
    
    return {type: value.type, output: "++" + value.output, left: false};
  }
  
  if (ij_expect("--") !== null) {
    let value = ij_parse_expression_2();
    
    ij_assert(ij_result_type("-", value.type, "int") === null, "Cannot apply the '--' operator to the type '" + value.type + "'.");
    ij_assert(!value.left, "Cannot apply the '--' operator to a non-lvalue.");
    
    return {type: value.type, output: "--" + value.output, left: false};
  }
  
  let value = ij_parse_expression_1();
  
  if (ij_expect("++") !== null) {
    ij_assert(ij_result_type("+", value.type, "int") === null, "Cannot apply the '++' operator to the type '" + value.type + "'.");
    ij_assert(!value.left, "Cannot apply the '++' operator to a non-lvalue.");
    
    return {type: value.type, output: value.output + "++", left: false};
  }
  
  if (ij_expect("--") !== null) {
    ij_assert(ij_result_type("-", value.type, "int") === null, "Cannot apply the '--' operator to the type '" + value.type + "'.");
    ij_assert(!value.left, "Cannot apply the '--' operator to a non-lvalue.");
    
    return {type: value.type, output: value.output + "--", left: false};
  }
  
  return value;
}

function ij_parse_expression_3() {
  let left = ij_parse_expression_2();
  let operator;
  
  while ((operator = ij_expect("(operator 1)")) !== null) {
    let right = ij_parse_expression_2();
    
    ij_assert(
      ij_result_type(operator.text, left.type, right.type) === null,
      "Cannot apply the '" + operator.text + "' operator to the types '" + left.type + "' and '" + right.type + "'."
    );
    
    left.type = ij_result_type(operator.text, left.type, right.type);
    
    if (left.type === "int") left.output = "parseInt(" + left.output + " " + operator.text + " " + right.output + ")";
    else left.output = "(" + left.output + " " + operator.text + " " + right.output + ")";
    
    left.left = false;
  }
  
  return left;
}

function ij_parse_expression_4() {
  let left = ij_parse_expression_3();
  let operator;
  
  while ((operator = ij_expect("(operator 2)")) !== null) {
    let right = ij_parse_expression_3();
    
    ij_assert(
      ij_result_type(operator.text, left.type, right.type) === null,
      "Cannot apply the '" + operator.text + "' operator to the types '" + left.type + "' and '" + right.type + "'."
    );
    
    left.type = ij_result_type(operator.text, left.type, right.type);
    
    if (left.type === "int") left.output = "parseInt(" + left.output + " " + operator.text + " " + right.output + ")";
    else left.output = "(" + left.output + " " + operator.text + " " + right.output + ")";
    
    left.left = false;
  }
  
  return left;
}

function ij_parse_expression_5() {
  let left = ij_parse_expression_4();
  let operator;
  
  while ((operator = ij_expect("(operator 3)")) !== null) {
    let right = ij_parse_expression_4();
    
    ij_assert(
      ij_result_type(operator.text, left.type, right.type) === null,
      "Cannot apply the '" + operator.text + "' operator to the types '" + left.type + "' and '" + right.type + "'."
    );
    
    left.type = ij_result_type(operator.text, left.type, right.type);
    left.output = "(" + left.output + " " + operator.text + " " + right.output + ")";
    left.left = false;
  }
  
  return left;
}

function ij_parse_expression_6() {
  let left = ij_parse_expression_5();
  let operator;
  
  while ((operator = ij_expect("(operator 4)")) !== null) {
    let right = ij_parse_expression_5();
    
    ij_assert(
      ij_result_type(operator.text, left.type, right.type) === null,
      "Cannot apply the '" + operator.text + "' operator to the types '" + left.type + "' and '" + right.type + "'."
    );
    
    left.type = ij_result_type(operator.text, left.type, right.type);
    left.output = "(" + left.output + " " + operator.text + " " + right.output + ")";
    left.left = false;
  }
  
  return left;
}

function ij_parse_expression_7() {
  let left = ij_parse_expression_6();
  let operator;
  
  while ((operator = ij_expect("(operator 5)")) !== null) {
    let right = ij_parse_expression_6();
    
    ij_assert(
      ij_result_type(operator.text, left.type, right.type) === null,
      "Cannot apply the '" + operator.text + "' operator to the types '" + left.type + "' and '" + right.type + "'."
    );
    
    left.type = ij_result_type(operator.text, left.type, right.type);
    left.output = "(" + left.output + " " + operator.text + " " + right.output + ")";
    left.left = false;
  }
  
  return left;
}

function ij_parse_expression_8() {
  let left = ij_parse_expression_7();
  let operator;
  
  while ((operator = ij_expect("(operator 6)")) !== null) {
    let right = ij_parse_expression_7();
    
    ij_assert(
      ij_result_type(operator.text, left.type, right.type) === null,
      "Cannot apply the '" + operator.text + "' operator to the types '" + left.type + "' and '" + right.type + "'."
    );
    
    left.type = ij_result_type(operator.text, left.type, right.type);
    left.output = "(" + left.output + " " + operator.text + " " + right.output + ")";
    left.left = false;
  }
  
  return left;
}

function ij_parse_expression_9() {
  let left = ij_parse_expression_8();
  
  if (ij_expect("?") !== null) {
    ij_assert_exist(left.type !== "boolean", "Expected a condition of type 'boolean', got '" + left.type + "'.");
    
    let value_1 = ij_parse_expression();
    ij_assert(ij_expect(":") === null, "Expected a colon, got 'TEXT'.");
    
    let value_2 = ij_parse_expression();
    
    ij_assert_exist(
      !(ij_similar_type(value_1.type, value_2.type) || ij_similar_type(value_2.type, value_1.type)),
      "Results in ternary operator must be of the same type, not '" + value_1.type + "' and '" + value_2.type + "'."
    );
    
    let type = value_1.type;
    if (value_2.type === "double") type = value_2.type;
    
    return {type: type, output: "((" + left.output + ") ? (" + value_1.output + ") : (" + value_2.output + "))", left: false};
  }
  
  return left;
}

function ij_parse_expression_10() {
  let left = ij_parse_expression_9();
  let operator = ij_expect("=");
  
  if (operator !== null) {
    ij_assert(!left.left, "Cannot assign a value to a non-lvalue.");
    
    let right = ij_parse_expression();
    let type = right.type;
    
    if (operator.text !== "=") {
      let operator_name = operator.text.slice(0, -1);
      type = ij_result_type(operator_name, left.type, type);
    }
    
    ij_assert_exist(!ij_similar_type(type, left.type), "Cannot convert type '" + type + "' to '" + left.type + "'.");
    return {type: left.type, output: left.output + " " + operator.text + " " + right.output, left: false};
  }
  
  return left;
}

function ij_parse_expression() {
  if (ij_words[ij_index].type === ";" || ij_words[ij_index].type === ")") return {type: "null", output: "", left: false};
  return ij_parse_expression_10(); // wrapper for highest level expression
}

function ij_parse_statement() {
  let output = "";
  
  if (ij_expect("if") !== null) {
    ij_assert(ij_expect("(") === null, "Expected an opening parenthesis, got 'TEXT'.");
    let value = ij_parse_expression();
    
    ij_assert_exist(value.type !== "boolean", "Expected a condition of type 'boolean', got '" + value.type + "'.");
    ij_assert(ij_expect(")") === null, "Expected a matching closing parenthesis, got 'TEXT'.");
    
    let next_brace = (ij_words[ij_index].type === "{");
    
    output += "if (" + value.output + ") " + ij_parse_statement().output;
    
    if (ij_expect("else") !== null) {
      if (!next_brace) output += "; ";
      output += "else " + ij_parse_statement().output;
    }
    
    return {type: "null", output: output, left: false};
  } else if (ij_expect("while") !== null) {
    ij_assert(ij_expect("(") === null, "Expected an opening parenthesis, got 'TEXT'.");
    let value = ij_parse_expression();
    
    ij_assert_exist(value.type !== "boolean", "Expected a condition of type 'boolean', got '" + value.type + "'.");
    ij_assert(ij_expect(")") === null, "Expected a matching closing parenthesis, got 'TEXT'.");
    
    output += "while (" + value.output + ") " + ij_parse_statement().output;
    return {type: "null", output: output, left: false};
  } else if (ij_expect("switch") !== null) {
    ij_assert(ij_expect("(") === null, "Expected an opening parenthesis, got 'TEXT'.");
    let value = ij_parse_expression();
    
    ij_assert(ij_expect(")") === null, "Expected a matching closing parenthesis, got 'TEXT'.");
    ij_assert(ij_expect("{") === null, "Expected an opening brace, got 'TEXT'.");
    
    output += "switch (" + value.output + ") {";
    
    if (ij_expect("}") === null) {
      while (true) {
        if (ij_expect("case") !== null) {
          let case_value = ij_parse_expression();
          ij_assert(!ij_similar_type(case_value.type, value.type), "Cannot convert type '" + case_value.type + "' to '" + value.type + "'.");
          
          ij_assert(ij_expect(":") === null, "Expected a colon, got 'TEXT'.");
          output += "case " + case_value.output + ": ";
        } else {
          output += ij_parse_statement().output + "; ";
        }
        
        if (ij_expect("}") !== null) break;
      }
    }
    
    output += "} ";
    return {type: "null", output: output, left: false};
  } else if (ij_expect("for") !== null) {
    ij_assert(ij_expect("(") === null, "Expected an opening parenthesis, got 'TEXT'.");
    ij_contexts.push({});
    
    let value_1 = ij_parse_statement();
    
    let value_2 = ij_parse_expression();
    ij_assert(ij_expect(";") === null, "Expected a semicolon, got 'TEXT'.");
    
    let value_3 = ij_parse_expression();
    ij_assert(ij_expect(")") === null, "Expected a matching closing parenthesis, got 'TEXT'.");
    
    ij_assert_exist(value_2.type !== "boolean" && value_2.output.length > 0, "Expected a condition of type 'boolean', got '" + value_2.type + "'.");
    
    output += "for (" + value_1.output + "; " + value_2.output + "; " + value_3.output + ") " + ij_parse_statement().output;
    ij_contexts.pop();
    
    return {type: "null", output: output, left: false};
  } else if (ij_expect("return") !== null) {
    let value = {type: "void", output: "", left: false};
    if (ij_words[ij_index].type !== ";") value = ij_parse_expression();
    
    ij_assert_exist(!ij_similar_type(value.type, ij_return_type), "Cannot convert type '" + value.type + "' to '" + ij_return_type + "'.");
    ij_assert(ij_expect(";") === null, "Expected a semicolon, got 'TEXT'.");
    
    return {type: ij_return_type, output: "return " + value.output, left: false};
  } else if (ij_expect("break") !== null) {
    ij_assert(ij_expect(";") === null, "Expected a semicolon, got 'TEXT'.");
    return {type: "null", output: "break", left: false};
  } else if (ij_expect("continue") !== null) {
    ij_assert(ij_expect(";") === null, "Expected a semicolon, got 'TEXT'.");
    return {type: "null", output: "continue", left: false};
  } else if (ij_expect("{") !== null) {
    ij_contexts.push({});
    output += "{";
    
    if (ij_expect("}") === null) {
      while (true) {
        output += ij_parse_statement().output + "; ";
        if (ij_expect("}") !== null) break;
      }
    }
    
    ij_contexts.pop();
    output += "}";
    
    return {type: "null", output: output, left: false};
  }
  
  let old_index = ij_index;
  
  let found_type = true;
  let final = false;
  let type;
  
  try {
    final = (ij_expect("final") !== null);
    type = ij_parse_type();
  } catch (error) {
    ij_index = old_index;
    found_type = false;
  }
  
  if (found_type) {
    let name = ij_expect("(name)");
    ij_assert(name === null, "'TEXT' is not a valid variable name.");
    
    ij_assert(type === "void", "A variable cannot be of type 'void'.");
    ij_assert(type.indexOf("[") >= 0, "Brackets must be placed after the name when declaring declaring variables.");
    
    while (true) {
      if (ij_expect("[") !== null) {
        type += "[]";
        ij_assert(ij_expect("]") === null, "Expected a matching closing bracket, got 'TEXT'.");
      } else if (ij_expect("{") !== null) {
        type += "{}";
        ij_assert(ij_expect("}") === null, "Expected a matching closing brace, got 'TEXT'.");
      } else {
        break;
      }
    }
    
    ij_assert(
      name.text in ij_contexts[ij_contexts.length - 1] && ij_contexts[ij_contexts.length - 1][name.text].type !== type,
      "A variable with the name '" + name.text + "' already exists."
    );
    
    if (final) output += "const " + ij_mangle(name.text);
    else output += "let " + ij_mangle(name.text);
    
    if (ij_expect("=") !== null) {
      let value;
      
      if (ij_words[ij_index].type === "{") {
        ij_assert(type.indexOf("[") < 0, "Expected a value of type '" + type + "', got an array literal.");
        value = ij_parse_array(type);
      } else {
        value = ij_parse_expression();
        ij_assert(!ij_similar_type(value.type, type), "Cannot convert type '" + value.type + "' to '" + type + "'.");
      }
      
      output += " = " + value.output;
    }
    
    ij_contexts[ij_contexts.length - 1][name.text] = {type: type, can_edit: !final};
    
    ij_assert(ij_expect(";") === null, "Expected a semicolon, got 'TEXT'.");
    return {type: type, output: output, left: false};
  }
  
  let value = ij_parse_expression();
  ij_assert(ij_expect(";") === null, "Expected a semicolon, got 'TEXT'.");
  
  return value;
}

function ij_quick_block() {
  let match = null;
  
  if (ij_expect("(") !== null) {
    match = ")";
  } else if (ij_expect("{") !== null) {
    match = "}";
  } else if (ij_expect("[") !== null) {
    match = "]";
  }
  
  while (ij_words[ij_index].type !== match) {
    ij_assert(ij_words[ij_index].type === "(eof)", "Expected symbol '" + match + "', but reached the end of the program.");
    
    if ("({[".indexOf(ij_words[ij_index].type) >= 0) {
      ij_quick_block();
    } else {
      ij_index++;
    }
  }
  
  ij_index++;
}

function ij_quick_class() {
  let class_name = ij_expect("(name)");
  ij_assert(class_name === null, "'TEXT' is not a valid class name.");
  
  ij_classes[ij_mangle(class_name.text)] = {properties: {is_class: {type: "void", can_edit: false}}, methods: {}};
  ij_assert(ij_expect("{") === null, "Expected an opening brace, got 'TEXT'.");
  
  while (ij_expect("}") === null) {
    let type, name;
    
    if (ij_words[ij_index].text === class_name.text && ij_words[ij_index + 1].type === "(") {
      ij_index++;
      
      type = class_name.text;
      name = class_name;
    } else {
      type = ij_parse_type();
      
      name = ij_expect("(name)");
      ij_assert(name === null, "'TEXT' is not a valid property or method name.");
    }
    
    if (ij_expect("(") !== null) {
      let args_output = "";
      
      let array = [type];
      let args = {};
      
      if (ij_expect(")") === null) {
        while (true) {
          let arg_type = ij_parse_type();
          
          let arg_name = ij_expect("(name)");
          ij_assert(arg_name === null, "'TEXT' is not a valid argument name.");
          
          while (true) {
            if (ij_expect("[") !== null) {
              arg_type += "[]";
              ij_assert(ij_expect("]") === null, "Expected a matching closing bracket, got 'TEXT'.");
            } else if (ij_expect("{") !== null) {
              arg_type += "{}";
              ij_assert(ij_expect("}") === null, "Expected a matching closing brace, got 'TEXT'.");
            } else {
              break;
            }
          }
          
          args[arg_name.text] = {type: arg_type, can_edit: true};
          args_output += ij_mangle(arg_name.text);
          
          array.push(arg_type);
          
          if (ij_expect(")") !== null) {
            break;
          }
          
          args_output += ", ";
          ij_assert(ij_expect(",") === null, "Expected a comma or a closing parenthesis in function declaration, got 'TEXT'.");
        }
      }
      
      if (!(ij_mangle(name.text) in ij_classes[ij_mangle(class_name.text)].methods)) ij_classes[ij_mangle(class_name.text)].methods[ij_mangle(name.text)] = [];
      ij_classes[ij_mangle(class_name.text)].methods[ij_mangle(name.text)].push(array);
      
      ij_quick_block();
      continue;
    } else {
      ij_assert(ij_mangle(name.text) in ij_classes[ij_mangle(class_name.text)].properties, "A property with the name '" + name.text + "' already exists.");
      
      ij_assert(type === "void", "A property cannot be of type 'void'.");
      ij_assert(ij_is_array(type) || ij_is_dict(type), "Brackets and braces must be placed after the name when declaring properties.");
      
      while (true) {
        if (ij_expect("[") !== null) {
          type += "[]";
          ij_assert(ij_expect("]") === null, "Expected a matching closing bracket, got 'TEXT'.");
        } else if (ij_expect("{") !== null) {
          type += "{}";
          ij_assert(ij_expect("}") === null, "Expected a matching closing brace, got 'TEXT'.");
        } else {
          break;
        }
      }
      
      
      if (ij_expect("=") !== null) {
        let value;
        
        if (ij_words[ij_index].type === "{") {
          ij_assert(!ij_is_array(type), "Expected a value of type '" + type + "', got an array literal.");
          value = ij_parse_array(type);
        } else {
          value = ij_parse_expression();
          ij_assert(!ij_similar_type(value.type, type), "Cannot convert type '" + value.type + "' to '" + type + "'.");
        }
      }
      
      
      ij_classes[ij_mangle(class_name.text)].properties[ij_mangle(name.text)] = {type: type, can_edit: true};
      
      ij_assert(ij_expect(";") === null, "Expected a semicolon, got 'TEXT'.");
    }
  }
  
  return {type: class_name.text, output: "", left: false};
}

function ij_parse_class() {
  let class_name = ij_expect("(name)");
  ij_assert(class_name === null, "'TEXT' is not a valid class name.");
  
  let output = "";
  output += "class " + ij_mangle(class_name.text) + "{constructor(func, args) {this[func].apply(this, args);}; ";
  
  ij_assert(ij_expect("{") === null, "Expected an opening brace, got 'TEXT'.");
  
  while (ij_expect("}") === null) {
    let final = false;
    let type, name;
    
    if (ij_words[ij_index].text === class_name.text && ij_words[ij_index + 1].type === "(") {
      ij_index++;
      
      type = class_name.text;
      name = class_name;
    } else {
      final = (ij_expect("final") !== null);
      type = ij_parse_type();
      
      name = ij_expect("(name)");
      ij_assert(name === null, "'TEXT' is not a valid property or method name.");
    }
    
    if (ij_expect("(") !== null) {
      let args_output = "";
      
      let array = [type];
      let args = {};
      
      if (ij_expect(")") === null) {
        while (true) {
          let arg_type = ij_parse_type();
          
          let arg_name = ij_expect("(name)");
          ij_assert(arg_name === null, "'TEXT' is not a valid argument name.");
          
          while (true) {
            if (ij_expect("[") !== null) {
              arg_type += "[]";
              ij_assert(ij_expect("]") === null, "Expected a matching closing bracket, got 'TEXT'.");
            } else if (ij_expect("{") !== null) {
              arg_type += "{}";
              ij_assert(ij_expect("}") === null, "Expected a matching closing brace, got 'TEXT'.");
            } else {
              break;
            }
          }
          
          args[arg_name.text] = {type: arg_type, can_edit: true};
          args_output += ij_mangle(arg_name.text);
          
          array.push(arg_type);
          
          if (ij_expect(")") !== null) {
            break;
          }
          
          args_output += ", ";
          ij_assert(ij_expect(",") === null, "Expected a comma or a closing parenthesis in function declaration, got 'TEXT'.");
        }
      }
      
      if (!(ij_mangle(name.text) in ij_classes[ij_mangle(class_name.text)].methods)) ij_classes[ij_mangle(class_name.text)].methods[ij_mangle(name.text)] = [];
      ij_classes[ij_mangle(class_name.text)].methods[ij_mangle(name.text)].push(array);
      
      let properties_context = {};
      
      for (let property in ij_classes[ij_mangle(class_name.text)].properties) {
        properties_context[property.replace("__ij_", "")] = ij_classes[ij_mangle(class_name.text)].properties[property];
      }
      
      ij_contexts.push(properties_context);
      ij_contexts[ij_contexts.length - 1]["this"] = {type: class_name.text, can_edit: true};
      
      ij_contexts.push(args);
      
      let old_return_type = ij_return_type;
      ij_return_type = type;
      
      ij_current_class = class_name.text;
      ij_current_level = ij_contexts.length - 2;
      
      output += ij_mangle_function(name.text, array) + "(" + args_output + ") {";
      ij_assert(ij_expect("{") === null, "Expected an opening brace, got 'TEXT'.");
      
      if (ij_expect("}") === null) {
        while (true) {
          output += ij_parse_statement().output + "; ";
          if (ij_expect("}") !== null) break;
        }
      }
      
      ij_current_class = null;
      ij_current_level = null;
      
      ij_return_type = old_return_type;
      
      ij_contexts.pop();
      ij_contexts.pop();
      
      output += "}; ";
      continue;
    } else {
      // ij_assert(name.text in ij_classes[class_name.text].properties, "A property with the name '" + name.text + "' already exists.");
      
      ij_assert(type === "void", "A property cannot be of type 'void'.");
      ij_assert(ij_is_array(type) || ij_is_dict(type), "Brackets and braces must be placed after the name when declaring properties.");
      
      while (true) {
        if (ij_expect("[") !== null) {
          type += "[]";
          ij_assert(ij_expect("]") === null, "Expected a matching closing bracket, got 'TEXT'.");
        } else if (ij_expect("{") !== null) {
          type += "{}";
          ij_assert(ij_expect("}") === null, "Expected a matching closing brace, got 'TEXT'.");
        } else {
          break;
        }
      }
      
      output += ij_mangle(name.text);
      
      if (ij_expect("=") !== null) {
        let value;
        
        if (ij_words[ij_index].type === "{") {
          ij_assert(!ij_is_array(type), "Expected a value of type '" + type + "', got an array literal.");
          value = ij_parse_array(type);
        } else {
          value = ij_parse_expression();
          ij_assert(!ij_similar_type(value.type, type), "Cannot convert type '" + value.type + "' to '" + type + "'.");
        }
        
        output += " = " + value.output;
      } else {
        output += " = undefined";
      }
      
      ij_classes[ij_mangle(class_name.text)].properties[ij_mangle(name.text)] = {type: type, can_edit: !final};
      
      ij_assert(ij_expect(";") === null, "Expected a semicolon, got 'TEXT'.");
      output += "; ";
    }
  }
  
  output += "}; ";
  return {type: class_name.text, output: output, left: false};
}

function ij_parse_global() {
  if (ij_expect(";") !== null) {
    return {type: "null", output: ""};
  }
  
  if (ij_expect("class") !== null) {
    if (!ij_complain) return ij_quick_class();
    return ij_parse_class();
  }
  
  let final = (ij_expect("final") !== null);
  let type = ij_parse_type();
  
  let name = ij_expect("(name)");
  ij_assert(name === null, "'TEXT' is not a valid variable or function name.");
  
  let output = "";
  
  if (ij_words[ij_index].type === "(") {
    ij_assert(final, "A function cannot have the modifier 'final'.");
    ij_index++;
    
    let array = [type];
    let args = {};
    
    if (ij_expect(")") === null) {
      while (true) {
        let arg_type = ij_parse_type();
        
        let arg_name = ij_expect("(name)");
        ij_assert(arg_name === null, "'TEXT' is not a valid argument name.");
        
        while (true) {
          if (ij_expect("[") !== null) {
            arg_type += "[]";
            ij_assert(ij_expect("]") === null, "Expected a matching closing bracket, got 'TEXT'.");
          } else if (ij_expect("{") !== null) {
            arg_type += "{}";
            ij_assert(ij_expect("}") === null, "Expected a matching closing brace, got 'TEXT'.");
          } else {
            break;
          }
        }
        
        args[arg_name.text] = {type: arg_type, can_edit: true};
        output += ij_mangle(arg_name.text);
        
        array.push(arg_type);
        
        if (ij_expect(")") !== null) {
          break;
        }
        
        output += ", ";
        ij_assert(ij_expect(",") === null, "Expected a comma or a closing parenthesis in function declaration, got 'TEXT'.");
      }
    }
    
    if (!(name.text in ij_functions)) ij_functions[name.text] = [];
    ij_functions[name.text].push(array);
    
    ij_contexts.push(args);
    
    let old_return_type = ij_return_type;
    ij_return_type = type;
    
    output = "function " + ij_mangle_function(name.text, array) + "(" + output + ") {";
    
    if (ij_complain) {
      ij_assert(ij_expect("{") === null, "Expected an opening brace, got 'TEXT'.");
      
      if (ij_expect("}") === null) {
        while (true) {
          output += ij_parse_statement().output + "; ";
          if (ij_expect("}") !== null) break;
        }
      }
    } else {
      ij_quick_block();
    }
    
    ij_return_type = old_return_type;
    
    ij_contexts.pop();
    output += "}";
    
    return {type: type, output: output, left: false};
  }
  
  ij_assert(type === "void", "A variable cannot be of type 'void'.");
  ij_assert(ij_is_array(type) || ij_is_dict(type), "Brackets and braces must be placed after the name when declaring declaring variables.");
  
  while (true) {
    if (ij_expect("[") !== null) {
      type += "[]";
      ij_assert(ij_expect("]") === null, "Expected a matching closing bracket, got 'TEXT'.");
    } else if (ij_expect("{") !== null) {
      type += "{}";
      ij_assert(ij_expect("}") === null, "Expected a matching closing brace, got 'TEXT'.");
    } else {
      break;
    }
  }
  
  ij_assert(
    name.text in ij_contexts[ij_contexts.length - 1] && ij_contexts[ij_contexts.length - 1][name.text].type !== type,
    "A variable with the name '" + name.text + "' already exists."
  );
  
  if (final) output += "const ";
  else output += "let ";
  
  output += ij_mangle(name.text);
  
  if (ij_expect("=") !== null) {
    let value;
    
    if (ij_words[ij_index].type === "{") {
      ij_assert(!ij_is_array(type), "Expected a value of type '" + type + "', got an array literal.");
      value = ij_parse_array(type);
    } else {
      value = ij_parse_expression();
      ij_assert(!ij_similar_type(value.type, type), "Cannot convert type '" + value.type + "' to '" + type + "'.");
    }
    
    output += " = " + value.output;
  }
  
  ij_contexts[ij_contexts.length - 1][name.text] = {type: type, can_edit: !final};
  
  ij_assert(ij_expect(";") === null, "Expected a semicolon, got 'TEXT'.");
  return {type: type, output: output, left: false};
}

function ij_init_context() {
  ij_functions["round"] = [["int", "double"]];
  ij_functions["floor"] = [["int", "double"]];
  ij_functions["ceil"] = [["int", "double"]];
  ij_functions["abs"] = [["int", "int"], ["double", "double"]];
  
  ij_functions["cos"] = [["double", "double"]];
  ij_functions["sin"] = [["double", "double"]];
  ij_functions["tan"] = [["double", "double"]];
  
  ij_functions["acos"] = [["double", "double"]];
  ij_functions["asin"] = [["double", "double"]];
  ij_functions["atan"] = [["double", "double"]];
  
  ij_functions["pow"] = [["double", "double", "double"]];
  ij_functions["sqrt"] = [["double", "double"]];
  ij_functions["log"] = [["double", "double"], ["double", "double", "double"]];
  ij_functions["ln"] = [["double", "double"]];
  
  ij_functions["millis"] = [["int"]];
  ij_functions["second"] = [["int"]];
  ij_functions["minute"] = [["int"]];
  ij_functions["hour"] = [["int"]];
  ij_functions["day"] = [["int"]];
  ij_functions["month"] = [["int"]];
  ij_functions["year"] = [["int"]];
  
  ij_functions["background"] = [["void", "double"], ["void", "double", "double", "double"], ["void", "double", "double", "double", "double"]];
  ij_functions["fill"] = [["void", "double"], ["void", "double", "double", "double"], ["void", "double", "double", "double", "double"]];
  ij_functions["stroke"] = [["void", "double"], ["void", "double", "double", "double"], ["void", "double", "double", "double", "double"]];
  
  ij_functions["noFill"] = [["void"]];
  
  ij_functions["strokeWeight"] = [["void", "double"]];
  ij_functions["noStroke"] = [["void"]];
  
  ij_functions["rect"] = [["void", "double", "double", "double", "double"]];
  ij_functions["ellipse"] = [["void", "double", "double", "double", "double"]];
  ij_functions["triangle"] = [["void", "double", "double", "double", "double", "double", "double"]];
  ij_functions["line"] = [["void", "double", "double", "double", "double"]];
  
  ij_functions["text"] = [["void", "String", "double", "double"], ["void", "double", "double", "double"], ["void", "char", "double", "double"], ["void", "boolean", "double", "double"]];
  ij_functions["image"] = [["void", "String", "double", "double"], ["void", "String", "double", "double", "double", "double"]];
  
  ij_functions["textWidth"] = [["double", "String"], ["double", "double"], ["double", "char"], ["double", "boolean"]];
  
  ij_functions["textFamily"] = [["void"], ["void", "String"]];
  ij_functions["textStyle"] = [["void"], ["void", "String"]];
  ij_functions["textSize"] = [["void", "int"]];
  
  ij_functions["random"] = [["double"], ["double", "double"], ["double", "double", "double"]];
  
  ij_functions["animate"] = [["void", "function"], ["void", "function", "double"]];
  ij_functions["loop"] = [["void", "function"], ["void", "function", "double"]];
  
  ij_functions["exit"] = [["void"]];
  ij_functions["noLoop"] = [["void"]];
  
  ij_functions["charAt"] = [["char", "String", "int"]];
  ij_functions["concat"] = [["String", "String", "String"]];
  ij_functions["compare"] = [["int", "String", "String"]];
  ij_functions["indexOf"] = [["int", "String", "char"]];
  
  ij_functions["println"] = [["void"], ["void", "String"], ["void", "double"], ["void", "char"], ["void", "boolean"]];
  ij_functions["print"] = [["void"], ["void", "String"], ["void", "double"], ["void", "char"], ["void", "boolean"]];
  
  ij_functions["readInteger"] = [["int"], ["int", "String"]];
  ij_functions["readDouble"] = [["double"], ["double", "String"]];
  ij_functions["readString"] = [["String"], ["String", "String"]];
  ij_functions["readChar"] = [["char"], ["char", "String"]];
  
  ij_functions["triggerAttack"] = [["void", "String"]];
  ij_functions["triggerRelease"] = [["void", "String"]];
  ij_functions["releaseAll"] = [["void"]];
  
  ij_contexts[0]["mousePressed"] = {type: "boolean", can_edit: false};
  ij_contexts[0]["mouseButton"] = {type: "int", can_edit: false};
  ij_contexts[0]["mouseX"] = {type: "int", can_edit: false};
  ij_contexts[0]["mouseY"] = {type: "int", can_edit: false};
  
  ij_contexts[0]["keyPressed"] = {type: "boolean", can_edit: false};
  ij_contexts[0]["key"] = {type: "String", can_edit: false};
  
  ij_contexts[0]["LEFTBUTTON"] = {type: "int", can_edit: false};
  ij_contexts[0]["MIDDLEBUTTON"] = {type: "int", can_edit: false};
  ij_contexts[0]["RIGHTBUTTON"] = {type: "int", can_edit: false};
  
  ij_contexts[0]["PI"] = {type: "double", can_edit: false};
  ij_contexts[0]["E"] = {type: "double", can_edit: false};
  
  ij_contexts[0]["true"] = {type: "boolean", can_edit: false};
  ij_contexts[0]["false"] = {type: "boolean", can_edit: false};
  
  ij_contexts[0]["null"] = {type: "null", can_edit: false};
}

function ij_quick_parser(words) {
  ij_init_context();
  
  ij_words = words;
  ij_index = 0;
  
  let output = "function m(s, d) {";
  output += "if (s.length === 0) return d; ";
  output += "let a = []; for (let i = 0; i < s[0]; i++) a.push(m(s.slice(1), d)); ";
  output += "if (Object.seal) Object.seal(a); ";
  output += "return a;}";
  
  while (ij_index < ij_words.length - 1) {
    output += ij_parse_global().output + "; ";
  }
  
  output += ij_mangle_function("main", ["void"]) + "();";
  return output;
}

function ij_parser(words) {
  ij_contexts = [{}];
  
  ij_ran_funcs = {};
  ij_functions = {};
  
  ij_complain = false;
  
  try {
    ij_quick_parser(words);
  } catch (error) {
    // nothing
  }
  
  ij_ran_funcs = {};
  
  ij_complain = true;
  let code = ij_quick_parser(words);
  
  return [code, ij_ran_funcs, ij_functions]
}
