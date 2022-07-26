function iJavaCompiler(on_stop) {
  let funcs = null;
  
  let errorHandler = null;
  let outputHandler = null;
  let inputStream = null;
  
  this.parse = function(source) {
    try {
      ij_lexer(source);
    } catch (error) {
      console.log(error);
      
      return [{
        id: 0,
        message: error[1],
        line: error[0],
        col: 0,
        severity: "error",
      }];
    }
    
    return [];
  }
  
  this.hasErrors = function(source) {
    try {
      ij_parser(ij_lexer(source));
    } catch (error) {
      return true;
    }
    
    return false;
  }
  
  this.getKeyPoints = function(source) {    
    return [];
  }
  
  this.getIcon = function(source) {
    return 0;
  }
  
  this.hasImage = function(source) {
    return false;
  }
  
  this.getCode = function(source) {
    try {
      return ij_parser(ij_lexer(source))[0];
    } catch (error) {
      return null;
    }
  };

  // Devuelve null si no llega a compilar el cdigo
  this.run = function(source, canvasid) {
    let code, ran_funcs, def_funcs;
    
    try {
      let data = ij_parser(ij_lexer(source));
      
      code = data[0];
      ran_funcs = data[1];
      def_funcs = data[2];
    } catch (error) {
      errorHandler.manage(error);
      return null;
    }
    
    if (funcs !== null) funcs.stop();
    outputHandler.clear();
    
    funcs = new ij_funcs(def_funcs, canvasid, outputHandler, errorHandler, on_stop);
    funcs.run(code);
    
    return (("loop" in ran_funcs) || ("animate" in ran_funcs));
  }
  
  this.runTest = function(source, inputStream) {
    return null;
  }
  
  this.stop = function() {
    if (funcs !== null) funcs.stop();
    funcs = null;
  }
  
  this.setInputStream = function(iostream) {
    inputStream = iostream;
  }
  
  this.setOutputHandler = function(oh) {
    outputHandler = oh;
  }
  
  this.setErrorHandler = function(eh) {
    errorHandler = eh;
  }
  
  this.getMD5Image = function() {
    return null;
  }
}
