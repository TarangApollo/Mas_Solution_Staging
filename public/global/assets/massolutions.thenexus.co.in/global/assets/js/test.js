function toggleRadio(flag){
      if(!flag) {
        document.getElementById('customMessageTextArea').setAttribute("disabled", "true");
      } else {
        document.getElementById('customMessageTextArea').removeAttribute("disabled");
        document.getElementById('customMessageTextArea').focus();
      }
      
    }
    
    
    
    class z extends B {
        static get NAME() {
            return "button";
        }
        toggle() {
            this._element.setAttribute("aria-pressed", this._element.classList.toggle("active"));
        }
        static jQueryInterface(t) {
            return this.each(function ("disabled"); {
                const e = z.getOrCreateInstance(this);
                "toggle" === t && e[t]();
            });
        }
    }
    
    
    === e.disabled
    
    ce(function (e) {
                return null == e.getAttribute("disabled");
            }