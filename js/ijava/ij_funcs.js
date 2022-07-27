let cached_images = {};

function ij_funcs(def_funcs, canvas_id, output_handler, error_handler, on_stop) {
  let canvas = document.getElementById(canvas_id);
  let context = canvas.getContext("2d");
  
  if (!canvas.getAttribute("tabindex")) canvas.setAttribute("tabindex", 0);
  canvas.focus();
  
  canvas.style["image-rendering"] = "pixelated";
  
  let handles = [];
  let synth = null;
  
  let keys_pressed = 0;
  
  let last_interval = null;
  let start_time = null;
  
  let fill_color = {r: 255, g: 255, b: 255, a: 255};
  let stroke_color = {r: 0, g: 0, b: 0, a: 255};
  
  let line_width = 1;
  let keys = {};
  
  let font_family = "arial";
  let font_style = "normal";
  let font_size = 14;
  
  context.fillStyle = "rgb(" + fill_color.r + ", " + fill_color.g + ", " + fill_color.b + ")";
  context.strokeStyle = "rgb(" + stroke_color.r + ", " + stroke_color.g + ", " + stroke_color.b + ")";
  
  context.lineCap ="butt";
  context.lineJoin ="miter";
  context.lineWidth = line_width;
  
  context.font = font_style + " " + parseInt(font_size) + "pt " + font_family;
  
  let func_onKeyPressed = null;
  let func_onKeyReleased = null;
  let func_onMousePressed = null;
  let func_onMouseReleased = null;
  
  let __ij_mousePressed = false;
  let __ij_mouseButton = 0;
  let __ij_mouseX = 0;
  let __ij_mouseY = 0;
  
  let __ij_keyPressed = false;
  let __ij_key = "";

  let __ij_LEFTBUTTON = 0;
  let __ij_RIGHTBUTTON = 1;
  let __ij_MIDDLEBUTTON = 2;
  
  const __ij_PI = Math.PI;
  const __ij_E = Math.E;
  
  const __ij_true = true;
  const __ij_false = false;
  
  const __ij_null = null;
  
  function __ij_int_double__round(x) {
    return Math.round(x);
  }

  function __ij_int_double__floor(x) {
    return Math.floor(x);
  }

  function __ij_int_double__ceil(x) {
    return Math.ceil(x);
  }
  
  function __ij_int_int__abs(x) {
    return parseInt(Math.abs(x));
  }

  function __ij_double_double__abs(x) {
    return Math.abs(x);
  }
  
  function __ij_double_double__cos(x) {
    return Math.cos(x);
  }

  function __ij_double_double__sin(x) {
    return Math.sin(x);
  }
  
  function __ij_double_double__tan(x) {
    return Math.tan(x);
  }
  
  function __ij_double_double__acos(x) {
    return Math.acos(x);
  }

  function __ij_double_double__asin(x) {
    return Math.asin(x);
  }
  
  function __ij_double_double__atan(x) {
    return Math.atan(x);
  }
  
  function __ij_double_double_double__pow(x, y) {
    return Math.pow(x, y);
  }
  
  function __ij_double_double__sqrt(x) {
    return Math.sqrt(x);
  }
  
  function __ij_double_double__log(x) {
    return Math.log10(x);
  }
  
  function __ij_double_double_double__log(x, y) {
    return Math.log(x) / Math.log(y);
  }
  
  function __ij_double_double__ln(x) {
    return Math.log(x);
  }
  
  function __ij_int__millis() {
    return parseInt((new Date()).getTime() - start_time.getTime());
  }
  
  function __ij_int__second() {
    return parseInt((new Date()).getSeconds());
  }
  
  function __ij_int__minute() {
    return parseInt((new Date()).getMinutes());
  }
  
  function __ij_int__hour() {
    return parseInt((new Date()).getHours());
  }
  
  function __ij_int__day() {
    return parseInt((new Date()).getDate());
  }
  
  function __ij_int__month() {
    return parseInt((new Date()).getMonth() + 1);
  }
  
  function __ij_int__year() {
    return parseInt((new Date()).getFullYear());
  }
  
  function __ij_void_double__background(x) {
    __ij_void_double_double_double__background(x, x, x);
  }

  function __ij_void_double_double_double__background(r, g, b) {
    __ij_void_double_double_double_double__background(r, g, b, 1);
  }

  function __ij_void_double_double_double_double__background(r, g, b, a) {
    context.fillStyle = "rgb("+ r + ", " + g +", "+ b + ")";
    context.globalAlpha = a;
    
    context.fillRect(0, 0, 320, 320);
    
    context.fillStyle = "rgb(" + fill_color.r + ", " + fill_color.g + ", " + fill_color.b + ")";
    context.globalAlpha = fill_color.a;
  }

  function __ij_void_double__fill(x) {
    fill_color.r = x;
    fill_color.g = x;
    fill_color.b = x;
    fill_color.a = 1;
    
    context.fillStyle = "rgb(" + fill_color.r + ", " + fill_color.g + ", " + fill_color.b + ")";
  }

  function __ij_void_double_double_double__fill(r, g, b) {
    fill_color.r = r;
    fill_color.g = g;
    fill_color.b = b;
    fill_color.a = 1;
    
    context.fillStyle = "rgb(" + fill_color.r + ", " + fill_color.g + ", " + fill_color.b + ")";
  }

  function __ij_void_double_double_double_double__fill(r, g, b, a) {
    fill_color.r = r;
    fill_color.g = g;
    fill_color.b = b;
    fill_color.a = a;
    
    context.fillStyle = "rgb(" + fill_color.r + ", " + fill_color.g + ", " + fill_color.b + ")";
  }

  function __ij_void_double__stroke(x) {
    stroke_color.r = x;
    stroke_color.g = x;
    stroke_color.b = x;
    stroke_color.a = 1;
    
    context.strokeStyle = "rgb(" + stroke_color.r + ", " + stroke_color.g + ", " + stroke_color.b + ")";
  }

  function __ij_void_double_double_double__stroke(r, g, b) {
    stroke_color.r = r;
    stroke_color.g = g;
    stroke_color.b = b;
    stroke_color.a = 1;
    
    context.strokeStyle = "rgb(" + stroke_color.r + ", " + stroke_color.g + ", " + stroke_color.b + ")";
  }

  function __ij_void_double_double_double_double__stroke(r, g, b, a) {
    stroke_color.r = r;
    stroke_color.g = g;
    stroke_color.b = b;
    stroke_color.a = a;
    
    context.strokeStyle = "rgb(" + stroke_color.r + ", " + stroke_color.g + ", " + stroke_color.b + ")";
  }
  
  function __ij_void__noFill() {
    fill_color.a = 0;
  }
  
  function __ij_void_double__strokeWeight(weight) {
    context.lineWidth = weight;
  }
  
  function __ij_void__noStroke() {
    fill_color.a = 0;
  }
  
  function __ij_void_double_double_double_double__rect(x, y, width, height) {
    if (line_width % 2) context.translate(0.5, 0.5);
    
    context.beginPath();
    context.rect(x, y, width, height);
    context.closePath();
    
    if (fill_color.a > 0) {
      context.globalAlpha = fill_color.a;
      context.fill();
    }
    
    if (stroke_color.a > 0) {
      context.globalAlpha = stroke_color.a;
      context.stroke();
    }
    
    if (line_width % 2) context.translate(-0.5, -0.5);
  }

  function __ij_void_double_double_double_double__ellipse(x, y, width, height) {
    if (line_width % 2) context.translate(0.5, 0.5);
    
    if (fill_color.a > 0) {
      context.globalAlpha = fill_color.a;
      context.fill();
    }
    
    if (stroke_color.a > 0) {
      context.globalAlpha = stroke_color.a;
      context.stroke();
    }
    
    if (line_width % 2) context.translate(-0.5, -0.5);
  }
  
  function __ij_void_double_double_double_double_double_double__triangle(x1, y1, x2, y2, x3, y3) {
    if (line_width % 2) context.translate(0.5, 0.5);
    
    context.beginPath();
    context.moveTo(x1, y1);
    context.lineTo(x2, y2);
    context.lineTo(x3, y3);
    context.lineTo(x1, y1);
    context.closePath();
    
    if (fill_color.a > 0) {
      context.globalAlpha = fill_color.a;
      context.fill();
    }
    
    if (stroke_color.a > 0) {
      context.globalAlpha = stroke_color.a;
      context.stroke();
    }
    
    if (line_width % 2) context.translate(-0.5, -0.5);
  }

  function __ij_void_double_double_double_double__line(x1, y1, x2, y2) {
    if (line_width % 2) context.translate(0.5, 0.5);
    
    context.beginPath();
    context.moveTo(x1, y1);
    context.lineTo(x2, y2);
    context.closePath();
    
    if (stroke_color.a > 0) {
      context.globalAlpha = stroke_color.a;
      context.stroke();
    }
    
    if (line_width % 2) context.translate(-0.5, -0.5);
  }
  
  function __ij_void_String_double_double__text(text, x, y) {
    if (fill_color.a > 0) {
      context.globalAlpha = fill_color.a;
      context.fillText(text, x, y);
    }
  }
  
  function __ij_void_double_double_double__text(text, x, y) {
    __ij_void_String_double_double__text(text.toString(), x, y);
  }
  
  function __ij_void_char_double_double__text(text, x, y) {
    __ij_void_String_double_double__text(text.toString(), x, y);
  }
  
  function __ij_void_boolean_double_double__text(text, x, y) {
    __ij_void_String_double_double__text(text.toString(), x, y);
  }
  
  function __ij_void_String_double_double__image(url, x, y) {
    if (url in cached_images) {
      if (cached_images[url].ready) {
        context.drawImage(cached_images[url], x, y);
      }
    } else {
      cached_images[url] = new Image();
      cached_images[url].ready = false;
      
      cached_images[url].onload = function() {
        this.ready = true;
      };
      
      cached_images[url].src = url;
      cached_images[url].id = url;
    }
  }

  function __ij_void_String_double_double_double_double__image(url, x, y, width, height) {
    if (url in cached_images) {
      if (cached_images[url].ready) {
        context.drawImage(cached_images[url], x, y, width, height);
      }
    } else {
      cached_images[url] = new Image();
      cached_images[url].ready = false;
      
      cached_images[url].onload = function() {
        this.ready = true;
      };
      
      cached_images[url].src = url;
      cached_images[url].id = url;
    }
  }
  
  function __ij_double_String__textWidth(text) {
    return context.measureText(text).width;
  }
  
  function __ij_double_double__textWidth(text) {
    return __ij_double_String__textWidth(text.toString());
  }
  
  function __ij_double_char__textWidth(text) {
    return __ij_double_String__textWidth(text.toString());
  }
  
  function __ij_double_boolean__textWidth(text) {
    return __ij_double_String__textWidth(text.toString());
  }
  
  function __ij_void__textFamily() {
    __ij_void_String__textFamily("arial");
  }
  
  function __ij_void_String__textFamily(family) {
    font_family = family;
    context.font = font_style + " " + parseInt(font_size) + "pt " + font_family;
  }
  
  function __ij_void__textStyle() {
    __ij_void_String__textStyle("normal");
  }
  
  function __ij_void_String__textStyle(style) {
    font_style = style;
    context.font = font_style + " " + parseInt(font_size) + "pt " + font_family;
  }
  
  function __ij_void_int__textSize(size) {
    font_size = size;
    context.font = font_style + " " + parseInt(font_size) + "pt " + font_family;
  }
  
  function __ij_double_double_double__random(min, max) {
    return min + Math.random() * (max - min);
  }
  
  function __ij_void_function__animate(func) {
    __ij_void_function_double__animate(func, 40);
  }
  
  function __ij_void_function_double__animate(func, delta) {
    if (last_interval !== null) clearInterval(last_interval);
    
    last_interval = setInterval(function() {
      try {
        func();
      } catch (error) {
        handle_error(error);
      }
    }, delta);
  }
  
  function __ij_void_function__loop(func) {
    __ij_void_function_double__loop(func, 40);
  }
  
  function __ij_void_function_double__loop(func, delta) {
    __ij_void_function_double__animate(func, delta);
  }
  
  function __ij_void__exit() {
    this.stop();
  }
  
  function __ij_void__noLoop() {
    this.stop();
  }
  
  function __ij_char_String_int__charAt(text, index) {
    return text.charAt(index);
  }
  
  function __ij_String_String_String__concat(text_1, text_2) {
    return text_1 + text_2;
  }
  
  function __ij_int_String_String__compare(text_1, text_2) {
    if (text_1 > text_2) return 1;
    if (text_1 < text_2) return -1;
    
    return 0;
  }
  
  function __ij_int_String_char__indexOf(text, chr) {
    return text.indexOf(chr);
  }
  
  function __ij_void__println() {
    __ij_void_String__println("");
  }
  
  function __ij_void__print() {
    __ij_void_String__print("");
  }
  
  function __ij_void_String__println(text) {
    __ij_void_String__print(text + "\n");
  }
  
  function __ij_void_String__print(text) {
    if (output_handler) output_handler.print(text);
    else console.log(text);
  }
  
  function __ij_void_double__println(text) {
    __ij_void_String__println(text.toString());
  }
  
  function __ij_void_double__print(text) {
    __ij_void_String__print(text.toString());
  }
  
  function __ij_void_char__println(text) {
    __ij_void_String__println(text.toString());
  }
  
  function __ij_void_char__print(text) {
    __ij_void_String__print(text.toString());
  }
  
  function __ij_void_boolean__println(text) {
    __ij_void_String__println(text.toString());
  }
  
  function __ij_void_boolean__print(text) {
    __ij_void_String__print(text.toString());
  }
  
  function __ij_int__readInteger() {
    return __ij_int_String__readInteger("Enter an integer:");
  }
  
  function __ij_int_String__readInteger(text) {
    return parseInt(__ij_String_String__readString(text));
  }
  
  function __ij_double__readDouble() {
    return __ij_double_String__readDouble("Enter a real number:");
  }
  
  function __ij_double_String__readDouble(text) {
    return parseFloat(__ij_String_String__readString(text));
  }
  
  function __ij_String__readString() {
    return __ij_String_String__readString("Enter a string:");
  }
  
  function __ij_String_String__readString(text) {
    return window.prompt(text, "");
  }
  
  function __ij_char__readChar() {
    return __ij_char_String__readChar("Enter a character:");
  }
  
  function __ij_char_String__readChar(text) {
    return __ij_String_String__readString(text).charAt(0);
  }
  
  function __ij_void_String__triggerAttack(note) {
    if (synth !== null) synth.triggerAttack([note]);
  }
  
  function __ij_void_String__triggerRelease(note) {
    if (synth !== null) synth.triggerRelease([note]);
  }
  
  function __ij_void__releaseAll() {
    if (synth !== null) synth.releaseAll(0);
  }
  
  function do_stop() {
    if (last_interval !== null) clearInterval(last_interval);
    
    if (synth !== null) {
      synth.disconnect();
      synth.dispose();
    }
    
    for (let handle of handles) {
      if (canvas.removeEventListener) {
        canvas.removeEventListener(handle[0], handle[1], false);
      } else {
        canvas.detachEvent("on" + handle[0], handle[1]);
      }
    }
    
    if (on_stop !== null) on_stop();
  }
  
  function handle_error(error) {
    if (!("length" in error)) {
      error = [0, error.message];
    }
    
    do_stop();
    
    if (error_handler) error_handler.manage(error);
    else console.log(error);
  }
  
  function add_handle(name, func) {
    if (canvas.addEventListener) {
      canvas.addEventListener(name, func, false);
    } else {
      canvas.attachEvent("on" + name, func);
    }
    
    handles.push([name, func]);
  }
  
  function supress_key_event(event) {
    if (typeof event.preventDefault === "function") event.preventDefault();
    else if (typeof event.stopPropagation === "function") event.stopPropagation();
    
    return false;
  }
  
  function get_key_code(event) {
    let code = event.key || event.keyCode || event.which;
    
    if (typeof code == "string") {
      if (code === "ArrowUp") return "up";
      if (code === "ArrowDown") return "down";
      if (code === "ArrowLeft") return "left";
      if (code === "ArrowRight") return "right";
      
      return code.toLowerCase();
    }
    
    switch (code) {
      case 16: return "shift";
      case 17: return "control";
      case 18: return "alt";
      case 8: return "backspace";
      case 9: return "tab";
      case 10: return "enter";
      case 13: return "return";
      case 27: return "esc";
      case 127: return "delete";
      case 20: return "capslk";
      case 33: return "pgup";
      case 34: return "pgdn";
      case 35: return "end";
      case 36: return "home";
      case 37: return "left";
      case 38: return "up";
      case 39: return "right";
      case 40: return "down";
      case 91: return "left-meta";
      case 93: return "right-meta";
    }
    
    if (code >= 32 && code < 127) return String.fromCharCode(code).toLowerCase();
  }
  
  function on_key_press(event) {
    __ij_keyPressed = (keys_pressed > 0);
    return supress_key_event(event);
  }
  
  function on_key_up(event) {
    let code = get_key_code(event);
    
    if (code !== null) {
      let last_state = false;
      if (code in keys) last_state = keys[code];
      
      if (last_state) {
        if ("onKeyReleased" in def_funcs) {
          try {
            func_onKeyReleased(code);
          } catch (error) {
            handle_error(error);
          }
        }
        
        keys_pressed--;
      }
      
      keys[code] = false;
    }
    
    __ij_keyPressed = (keys_pressed > 0);
    return supress_key_event(event);
  }
  
  function on_key_down(event) {
    let code = get_key_code(event);
    
    if (code !== null) {
      let last_state = false;
      if (code in keys) last_state = keys[code];
      
      if (!last_state) {
        if ("onKeyPressed" in def_funcs) {
          try {
            func_onKeyPressed(code);
          } catch (error) {
            handle_error(error);
          }
        }
        
        __ij_key = code;
        keys_pressed++;
      }
      
      keys[code] = true;
    }
    
    __ij_keyPressed = (keys_pressed > 0);
    return supress_key_event(event);
  }
  
  function update_mouse(event) {
    let rect = canvas.getBoundingClientRect();
    
    __ij_mouseX = Math.floor(((event.clientX - rect.left) * 320.0) / rect.width);
    __ij_mouseY = Math.floor(((event.clientY - rect.top) * 320.0) / rect.height);
  }
  
  function on_mouse_move(event) {
    update_mouse(event);
  }
  
  function on_mouse_out(event) {
    update_mouse(event);
  }
  
  function on_mouse_over(event) {
    update_mouse(event);
  }
  
  function on_mouse_down(event) {
    update_mouse(event);
    
    if (event.which === 1) __ij_mouseButton = __ij_LEFTBUTTON;
    if (event.which === 2) __ij_mouseButton = __ij_MIDDLEBUTTON;
    if (event.which === 3) __ij_mouseButton = __ij_RIGHTBUTTON;
    
    if (!__ij_mousePressed) {
      if ("onMousePressed" in def_funcs) {
        try {
          func_onMousePressed();
        } catch (error) {
          handle_error(error);
        }
      }
      
      __ij_mousePressed = true;
    }
  }
  
  function on_mouse_up(event) {
    update_mouse(event);
    
    if (__ij_mousePressed) {
      if ("onMouseReleased" in def_funcs) {
        try {
          func_onMouseReleased();
        } catch (error) {
          handle_error(error);
        }
      }
      
      __ij_mousePressed = false;
    }
  }
  
  this.run = function(code) {
    add_handle("keypress", on_key_press);
    add_handle("keyup", on_key_up);
    add_handle("keydown", on_key_down);
    
    add_handle("mousemove", on_mouse_move);
    add_handle("mouseout", on_mouse_out);
    add_handle("mouseover", on_mouse_over);
    add_handle("mousedown", on_mouse_down);
    add_handle("mouseup", on_mouse_up);
    
    add_handle("mousedown", function(event) {
      canvas.focus();
      return false;
    });
    
    add_handle("contextmenu", function(event) {
      event.preventDefault();
      event.stopPropagation();
    });
    
    synth = new Tone.PolySynth({
      options: {
        volume: -15.0,
        
        envelope: {
          attack: 0.0,
          decay: 10.0,
          sustain: 0.0,
          release: 0.25,
        },
        
        oscillator: {
          type: "square",
        },
      }
    }).toDestination();
    
    start_time = new Date();
    
    try {
      let new_code = "";
      
      if ("onKeyPressed" in def_funcs) new_code += "func_onKeyPressed = " + ij_mangle_function("onKeyPressed", def_funcs["onKeyPressed"][0]) + "; ";
      if ("onKeyReleased" in def_funcs) new_code += "func_onKeyReleased = " + ij_mangle_function("onKeyReleased", def_funcs["onKeyReleased"][0]) + "; ";
      if ("onMousePressed" in def_funcs) new_code += "func_onMousePressed = " + ij_mangle_function("onMousePressed", def_funcs["onMousePressed"][0]) + "; ";
      if ("onMouseReleased" in def_funcs) new_code += "func_onMouseReleased = " + ij_mangle_function("onMouseReleased", def_funcs["onMouseReleased"][0]) + "; ";
      
      new_code += code;
      eval(new_code);
    } catch (error) {
      handle_error(error);
    }
  };
  
  this.stop = function() {
    do_stop();
  };
}
