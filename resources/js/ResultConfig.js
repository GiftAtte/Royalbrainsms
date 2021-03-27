import Axios from 'axios'
export default class ResultConfig {
       
    constructor(config_settings) {
       
        this.config_settings=config_settings
       
    }


    isCPosition() {
        
        this.config_settings.forEach(setting => {
            if (setting.name === "Show Class Position" && setting.status === true) {
                return true;
            }
         });
    }
   
     isRSummary() {
        
        conSetting.forEach(setting => {
           
            if ((setting.name === "Show Result Summary" && setting.status === 1)) {
                console.log(setting)
                return true
                
            } else {
                return 0
            }
        });
    }
    isPStatus() {
        this.config_settings.forEach(setting => {
            if (setting.name === "Show Progress Status" && setting.status === true)
            {
                return true;
            }
        });
    }
    isCSPosition() {
        this.config_settings.forEach(setting => {
            if (setting.name === "Show Class Subject Position" && setting.status === true)
            {
                return true;
            }
        });
    }
    isCSHScore() {
        this.config_settings.forEach(setting => {
            if (setting.name === "Show Class Arm Position" && setting.status === true)
            {
                return true;
            }
        });
    }
    isCAPosition() {
        this.config_settings.forEach(setting => {
            if (setting.name === "Show Class Arm Position" && setting.status === true)
            {
                return true;
            }
        });
    }
    isCSLScore() {
        this.config_settings.forEach(setting => {
            if (setting.name === "Show Class Subject Lowest Score" && setting.status === true)
            {
                return true;
            }
        });
    }
    isCAScore() {
        this.config_settings.forEach(setting => {
            if (setting.name === "Show Class Average Score" && setting.status === true)
            {
                return true;
            }
        });
    }
    isASPosition() {
        this.config_settings.forEach(setting => {
            if (setting.name === "Show Arm Subject Position" && setting.status === true)
            {
                return true;
            }
        });
    }
    isAHScore() {
        this.config_settings.forEach(setting => {
            if (setting.name === "Show Arm Subject Highest Score" && setting.status === true)
            {
                return true;
            }
        });
    }
    isASAScore() {
        this.config_settings.forEach(setting => {
            if (setting.name === "Show Arm Subject Average Score" && setting.status === true)
            {
                return true;
            }
        });
    }
  
    
    isGFormula() {
        config_settings.forEach(setting => {
            if (setting.name === "Show Grading Formula" && setting.status === true)
            {
                return true;
            }
        });
    }

    isLDomain() {
        config_settings.forEach(setting => {
            if (setting.name === "Show Learning Domain" && setting.status === true)
            {
                return true;
            }
        });
    }
   
}

