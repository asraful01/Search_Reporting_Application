function convertJSON() {
    var resultsObject = {"Result" : []};
  var name = "searchresult" + ".json";
    var x = document.getElementsByClassName("contentdiv")[0];
    var dresult = x.children;
    for (var i = 0; i < dresult.length; i++) {
  if (dresult[i].children[0].checked) {
      var title = dresult[i].children[1].innerHTML;
      var url =   dresult[i].children[2].innerHTML;
      var description = dresult[i].children[3].innerHTML;
      var result = {"title": title, "url":url, "description":description};
      resultsObject["Result"].push(result);
  }
    }
    download(JSON.stringify(resultsObject), name);
}

function convertCSV() {
    var name = "searchresult"+ ".csv";
    var x = document.getElementsByClassName("contentdiv")[0];
    var dresult = x.children;
    var result = "";
    for (var i = 0; i < dresult.length; i++) {
        if (dresult[i].children[0].checked) {
            var title = dresult[i].children[1].innerHTML;
      title = title.replace(',','');
            var url =   dresult[i].children[2].innerHTML;
      url = url.replace(',','');
            var description = dresult[i].children[3].innerHTML;
      description = description.replace(',','');
      var result = result + title + "," + url + "," + description+"\n";
        }
    }
    result = result.trim();
    download(result, name);
}

function convertXML() {
    var name = "searchresult" + ".xml";
    var x = document.getElementsByClassName("contentdiv")[0];
    var dresult = x.children;
    var result = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<results>\n";
    for (var i = 0; i < dresult.length; i++) {
        if (dresult[i].children[0].checked) {
            var title = dresult[i].children[1].innerHTML;
            title = title.replace(',','');
            var url =   dresult[i].children[2].innerHTML;
            url = url.replace(',','');
            var description = dresult[i].children[3].innerHTML;
            description = description.replace(',','');
            var result = result + "<result>\n<title>" + title + "</title>\n"
            + "<url>" + url + "</url>\n" + "<description>" 
            + description + "</description>\n</result>\n";
        }
    }
    result += "</results>";
    download(result, name);
}

function download(data, name) {
    var a = document.getElementById("a");
    var file = new Blob([data], {type:  'text/plain'});
    a.href = URL.createObjectURL(file);
    a.download = name;
  a.click();
  }

window.onload = function() {
    var fileInput = document.getElementById('file-upload');
    var fileDisplayArea = document.getElementsByClassName('contentdiv')[0];
    
    fileInput.addEventListener('change', function(e) {
      var file = fileInput.files[0];
      var reader = new FileReader();
      

      reader.onload = function(e) {
        var extension = fileInput.value.split(".")[1];
        var fileText = reader.result;

        if (extension === "xml"){
          var parser = new DOMParser();
          var doc = parser.parseFromString(fileText, "text/xml");
          var results = doc.getElementsByTagName("result");

          for (var i = 0; i < results.length; i++) {
            var container = document.createElement("div");
            container.className = "result";
            var checkb = document.createElement("input");
            checkb.type = "checkbox";
            
            var link = document.createElement("a");
            link.className = "title";
            link.href = doc.getElementsByTagName("url")[i].textContent;
            link.innerHTML = doc.getElementsByTagName("title")[i].textContent;
            
            var url = document.createElement("p");
            url.className = "link";
            url.innerHTML = doc.getElementsByTagName("url")[i].textContent;
            
            var desc = document.createElement("p");
            desc.className = "description";
            desc.innerHTML = doc.getElementsByTagName("description")[i].textContent;
            
            var br = document.createElement("br");
            container.appendChild(checkb);
            container.appendChild(link);
            container.appendChild(url);
            container.appendChild(desc);
            container.appendChild(br);

        fileDisplayArea.appendChild(container);
    }
      }
      else if (extension === "json") {
    let obj = JSON.parse(fileText);
    let results = obj.Result;
    for (var i = 0; i < results.length; i++) {
        var container = document.createElement("div");
        container.className = "result";
        var checkb = document.createElement("input");
        checkb.type = "checkbox";

        var link = document.createElement("a");
        link.className = "title";
        link.href = results[i].url;
        link.innerHTML = results[i].title;

        var url = document.createElement("p");
        url.className = "link";
        url.innerHTML = results[i].url;

        var desc = document.createElement("p");
        desc.className = "description";
        desc.innerHTML = results[i].description;



        var br = document.createElement("br");
        container.appendChild(checkb);
        container.appendChild(link);
        container.appendChild(url);
        container.appendChild(desc);
        container.appendChild(br);
        fileDisplayArea.appendChild(container);
    }
      }
      else if (extension === "csv") {
    var content = fileText.split('\n');
    for (var i = 0; i < content.length; i++) {
        var elements = content[i].split(',');
        var container = document.createElement("div");
        container.className = "result";
        var checkb = document.createElement("input");
        checkb.type = "checkbox";

        var link = document.createElement("a");
        link.className = "title";
        link.href = elements[1];
        link.innerHTML = elements[0];

        var url = document.createElement("p");
        url.className = "link";
        url.innerHTML = elements[1];

        var desc = document.createElement("p");
        desc.className = "description";
        desc.innerHTML = elements[2];

        var br = document.createElement("br");
        container.appendChild(checkb);
        container.appendChild(link);
        container.appendChild(url);
        container.appendChild(desc);
        container.appendChild(br);
        fileDisplayArea.appendChild(container);
    }
      }
  }
  
  reader.readAsText(file); 
    });
}
