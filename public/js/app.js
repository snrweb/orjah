window.addEventListener("DOMContentLoaded", function() {
  document
    .getElementById("nodeForm")
    .addEventListener("submit", function(event) {
      event.preventDefault();

      let email = document.getElementById("email").value;
      let password = document.getElementById("password").value;
      let formdata = {};

      formdata.email = email;
      formdata.password = password;

      let request = new XMLHttpRequest();
      request.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
          console.log(request.responseText);
        }
      };

      request.onerror = function() {};
      request.open("POST", "/", true);
      request.setRequestHeader(
        "Content-Type",
        "application/json; charset=utf8"
      );
      request.send(JSON.stringify(formdata));
    });
});

//this function handles the slider components on the home page
window.addEventListener("DOMContentLoaded", function() {
  var count = 3;

  function getCount() {
    if (count < 1) {
      count = 3;
    }

    for (let i = 3; i >= 1; i--) {
      document.querySelector(".Slides" + i).classList.remove("SlideLeft");
    }

    document.querySelector(".Slides" + count).classList.add("SlideLeft");
    document.querySelector(".Slides" + count).classList.remove("SlideRight");

    if (count === 1) {
      document.querySelector(".Slides" + 3).classList.add("SlideRight");
    } else {
      document
        .querySelector(".Slides" + (count - 1))
        .classList.add("SlideRight");
    }
    count = count - 1;
  }

  setInterval(getCount, 3000);
});
