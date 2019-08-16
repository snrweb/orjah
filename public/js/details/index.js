const ROOT = "http://localhost/orjah/api/";

const alertMsg = (className, errorMessage) => {
  let elem = document.querySelector("#alert");
  elem.classList.add(className);
  elem.innerHTML = errorMessage;
};

const hideAlertMsg = () => {
  let alertElem = document.querySelector("#alert");

  if (alertElem.classList.contains("error-alert")) {
    alertElem.classList.remove("error-alert");
  }

  if (alertElem.classList.contains("success-alert")) {
    alertElem.classList.remove("success-alert");
  }
  alertElem.innerHTML = "";
};

//This function handles the switching of product images
//in column one of details page
window.addEventListener("DOMContentLoaded", () => {
  const imageTwoV = document.querySelector("#d-ProductImgLists-2");
  const imageThreeV = document.querySelector("#d-ProductImgLists-3");
  const imageTwoH = document.querySelector("#d-ProductImgLists-h2");
  const imageThreeH = document.querySelector("#d-ProductImgLists-h3");

  const removeIndex = childNumber => {
    document.querySelector("#d-ProductMainImg img:nth-child(1)").style.zIndex =
      "";

    if (imageTwoV) {
      document.querySelector(
        "#d-ProductMainImg img:nth-child(2)"
      ).style.zIndex = "";
    }

    if (imageThreeV) {
      document.querySelector(
        "#d-ProductMainImg img:nth-child(3)"
      ).style.zIndex = "";
    }

    document.querySelector(
      `#d-ProductMainImg img:nth-child(${childNumber})`
    ).style.zIndex = 2;
  };

  document
    .querySelector("#d-ProductImgLists-1")
    .addEventListener("click", () => {
      removeIndex(1);
    });

  if (imageTwoV) {
    imageTwoV.addEventListener("click", () => {
      removeIndex(2);
    });
  }

  if (imageThreeV) {
    imageThreeV.addEventListener("click", () => {
      removeIndex(3);
    });
  }

  document
    .querySelector("#d-ProductImgLists-h1")
    .addEventListener("click", () => {
      removeIndex(1);
    });

  if (imageTwoH) {
    imageTwoH.addEventListener("click", () => {
      removeIndex(2);
    });
  }

  if (imageThreeH) {
    imageThreeH.addEventListener("click", () => {
      removeIndex(3);
    });
  }

  document.querySelector("#d-SendOrderBtn").addEventListener("click", e => {
    e.preventDefault();
    const orderQty = document.querySelector("#d-orderQuantity").value;
    const productId = e.target.getAttribute("data-pid");
    let formdata = new FormData();

    formdata.append("quantity", orderQty);

    fetch(`${ROOT}details/sendOrder/${productId}`, {
      method: "post",
      body: formdata
    })
      .then(res => res.text())
      .then(text => {
        if (text == "true") {
          alertMsg("success-alert", "Order has been sent");
          let navOrderNotifElem = document.querySelector("#navBarOrders-span");
          let navOrderNotifValue = parseInt(navOrderNotifElem.innerHTML) + 1;
          navOrderNotifElem.innerHTML = navOrderNotifValue;
        } else if (text == "false") {
          alertMsg("error-alert", "An error occurred");
        } else {
          alertMsg("error-alert", "Please login as a buyer");
        }
        setTimeout(hideAlertMsg, 5000);
      })
      .catch(err => {
        console.log(err);
      });
  });

  document.querySelector("#d-ToggleCartBtn").addEventListener("click", e => {
    e.preventDefault();
    const status = e.target.getAttribute("data-status");
    const productId = e.target.getAttribute("data-pid");
    let url = `${ROOT}details/addToBasket/${productId}`;
    let navBasketNotifElem = document.querySelector("#navBarBasket-span");

    if (status == "remove") {
      url = `${ROOT}details/removeFromBasket/${productId}`;
    }
    fetch(url)
      .then(res => res.text())
      .then(text => {
        if (text == "trueA") {
          alertMsg("success-alert", "Product added to basket");
          e.target.innerHTML = "Remove From Cart";
          e.target.removeAttribute("data-status");
          e.target.setAttribute("data-status", "remove");
          let navBasketNotifValue = parseInt(navBasketNotifElem.innerHTML) + 1;
          navBasketNotifElem.innerHTML = navBasketNotifValue;
        } else if (text == "falseA") {
          alertMsg("error-alert", "An error occurred");
        } else if (text == "trueR") {
          alertMsg("success-alert", "Product removed from basket");
          e.target.innerHTML = "Add To Cart";
          e.target.removeAttribute("data-status");
          e.target.setAttribute("data-status", "add");
          let navBasketNotifValue = parseInt(navBasketNotifElem.innerHTML) - 1;
          navBasketNotifElem.innerHTML = navBasketNotifValue;
        } else if (text == "falseR") {
          alertMsg("error-alert", "An error occurred");
        } else {
          alertMsg("error-alert", text);
        }
        setTimeout(hideAlertMsg, 5000);
      })
      .catch(err => {
        console.log(err);
      });
  });
});
