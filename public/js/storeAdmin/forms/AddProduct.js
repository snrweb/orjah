import React, { Component } from "react";

import Mark from "./../../../images/svg/mark.svg";

class AddProduct extends Component {
  constructor(props) {
    super(props);

    this.state = {
      storeDetails: {},
      categories: [],
      productName: "",
      productPrice: 0,
      subCat: [],
      storeMenu: "",
      selectedCat: "",
      selectedSubCat: "",
      imageOne: {},
      imageTwo: {},
      imageThree: {},
      productDetails: ""
    };

    this.onFocus = this.onFocus.bind(this);
    this.onChange = this.onChange.bind(this);
    this.getCat = this.getCat.bind(this);
    this.getSubCat = this.getSubCat.bind(this);
    this.getStoreMenu = this.getStoreMenu.bind(this);

    this.onChangeImageOne = this.onChangeImageOne.bind(this);
    this.onChangeImageTwo = this.onChangeImageTwo.bind(this);
    this.onChangeImageThree = this.onChangeImageThree.bind(this);

    this.onSubmit = this.onSubmit.bind(this);
    this.alertMsg = this.alertMsg.bind(this);
    this.validateImage = this.validateImage.bind(this);
    this.previewImage = this.previewImage.bind(this);
    this.enableImageSelect = this.enableImageSelect.bind(this);
  }

  componentDidMount() {
    document.querySelector(".s-addProduct-input-file-w2").style.background =
      "#eee";
    document.querySelector(
      ".s-addProduct-input-file-w2 button"
    ).style.background = "#7f70b4";

    document.querySelector(".s-addProduct-input-file-w3").style.background =
      "#eee";
    document.querySelector(
      ".s-addProduct-input-file-w3 button"
    ).style.background = "#7f70b4";

    fetch(`${this.props.root}stores/admin/addProduct`)
      .then(res => res.json())
      .then(res => {
        this.setState({
          storeDetails: res.store_details,
          categories: res.categories
        });
      });
  }

  getStoreMenu(e) {
    e.preventDefault();
    const elems = document.getElementsByClassName("s-storeMenu-options").length;
    for (let i = 0; i < elems; i++) {
      document.querySelectorAll(".s-storeMenu-options")[i].style.background =
        "";
      document.querySelectorAll(".s-storeMenu-options")[i].style.color = "";
      document.querySelectorAll(".s-storeMenu-options-mark")[i].style.display =
        "";
    }

    this.setState({ storeMenu: e.target.getAttribute("data-name") });

    e.target.style.background = "#7f70b4";
    e.target.style.color = "white";
    e.target.querySelector(".s-storeMenu-options-mark").style.display =
      "inline";
  }

  getCat(e) {
    e.preventDefault();
    const elems = document.getElementsByClassName("s-catOPtions").length;
    for (let i = 0; i < elems; i++) {
      document.querySelectorAll(".s-catOPtions")[i].style.background = "";
      document.querySelectorAll(".s-catOPtions")[i].style.color = "";
      document.querySelectorAll(".s-catOPtions-mark")[i].style.display = "";
    }

    this.setState({ selectedCat: e.target.getAttribute("data-name") });
    if (
      Object.values(
        this.state.categories[this.props.store.store_category][
          e.target.getAttribute("data-name")
        ]
      ) != undefined
    ) {
      this.setState({
        subCat: Object.values(
          this.state.categories[this.props.store.store_category][
            e.target.getAttribute("data-name")
          ]
        )
      });
    }

    e.target.style.background = "#7f70b4";
    e.target.style.color = "white";
    e.target.querySelector(".s-catOPtions-mark").style.display = "inline";
  }

  getSubCat(e) {
    e.preventDefault();
    const elems = document.getElementsByClassName("s-scatOPtions").length;
    for (let i = 0; i < elems; i++) {
      document.querySelectorAll(".s-scatOPtions")[i].style.background = "";
      document.querySelectorAll(".s-scatOPtions")[i].style.color = "";
      document.querySelectorAll(".s-scatOPtions-mark")[i].style.display = "";
    }

    this.setState({ selectedSubCat: e.target.getAttribute("data-name") });

    e.target.style.background = "#7f70b4";
    e.target.style.color = "white";
    e.target.querySelector(".s-scatOPtions-mark").style.display = "inline";
  }

  alertMsg(elem, className, errorMessage) {
    elem.classList.add(className);
    elem.innerHTML = errorMessage;
  }

  onFocus() {
    let alertElem = document.querySelector("#alert");

    if (alertElem.classList.contains("error-alert")) {
      alertElem.classList.remove("error-alert");
    }

    if (alertElem.classList.contains("success-alert")) {
      alertElem.classList.remove("success-alert");
    }
    alertElem.innerHTML = "";
  }

  validateImage(image) {
    let alertElem = document.querySelector("#alert");
    if (image.name == "") {
      this.alertMsg(alertElem, "error-alert", "Please select an image");
      this.setState({ hasError: true });
      return false;
    }

    let extension = image.type
      .split("/")
      .pop()
      .toLowerCase();
    if (["jpg", "png", "jpeg"].indexOf(extension) == -1) {
      this.alertMsg(
        alertElem,
        "error-alert",
        "The selected file is not an image"
      );
      return false;
    }

    if (image.size > 4500000) {
      this.alertMsg(
        alertElem,
        "error-alert",
        "Image should not be more than 4mb"
      );
      return false;
    }
    return true;
  }

  enableImageSelect(number) {
    document.querySelector(
      `.s-addProduct-input-file-w${number}`
    ).style.background = "white";
    document.querySelector(
      `.s-addProduct-input-file-w${number} button`
    ).style.background = "#523f96";

    document
      .querySelector(`.s-addProduct-input-file-w${number} input`)
      .removeAttribute("disabled");
  }

  previewImage(image, number) {
    document.querySelector(`#s-addProduct-btn-${number}`).innerHTML =
      image.name;
    let preview = document.querySelector(`#s-addProduct-img-${number}`);
    preview.style.display = "block";
    preview.src = URL.createObjectURL(image);
  }

  onChange(e) {
    this.setState({ [e.target.name]: e.target.value });
  }

  onChangeImageOne(e) {
    let image = e.target.files[0];
    if (this.validateImage(image)) {
      this.enableImageSelect(2);
      this.setState({ imageOne: image });
      this.previewImage(image, 1);
    }
  }

  onChangeImageTwo(e) {
    let image = e.target.files[0];
    if (this.validateImage(image)) {
      this.enableImageSelect(3);
      this.setState({ imageTwo: image });
      this.previewImage(image, 2);
    }
  }

  onChangeImageThree(e) {
    let image = e.target.files[0];
    if (this.validateImage(image)) {
      this.setState({ imageThree: image });
      this.previewImage(image, 3);
    }
  }

  onSubmit(e) {
    e.preventDefault();
    let alertElem = document.querySelector("#alert");

    if (this.state.productName.trim() == "") {
      this.alertMsg(alertElem, "error-alert", "Product name is empty");
      return;
    }

    const price = String(this.state.productPrice);

    if (price == "0" || !price.match(/^[0-9]+$/)) {
      this.alertMsg(
        alertElem,
        "error-alert",
        "Price should only contain numbers"
      );
      return;
    }

    if (this.state.selectedCat.trim() == "") {
      this.alertMsg(
        alertElem,
        "error-alert",
        "Select a category for your product"
      );
      return;
    }

    if (this.state.selectedSubCat.trim() == "") {
      this.alertMsg(
        alertElem,
        "error-alert",
        "Select a sub category for your product"
      );
      return;
    }

    if (this.state.imageOne.name == undefined) {
      this.alertMsg(
        alertElem,
        "error-alert",
        "You have not selected any image"
      );
      return;
    }

    if (this.state.productDetails.trim() == "") {
      this.alertMsg(alertElem, "error-alert", "Product detail is required");
      return;
    }

    let formdata = new FormData();
    formdata.append("product_name", this.state.productName);
    formdata.append("product_price", this.state.productPrice);
    formdata.append("store_menu", this.state.storeMenu);
    formdata.append("product_cat", this.state.selectedCat);
    formdata.append("product_sub_cat", this.state.selectedSubCat);
    formdata.append("product_image_one", this.state.imageOne);
    formdata.append("product_image_two", this.state.imageTwo);
    formdata.append("product_image_three", this.state.imageThree);
    formdata.append("product_details", this.state.productDetails);

    fetch(`${this.props.root}stores/admin/addProduct`, {
      method: "post",
      body: formdata
    })
      .then(res => res.text())
      .then(text => {
        if (text == "true") {
          this.props.resetState(true);
          this.alertMsg(alertElem, "success-alert", "Product added to store");
        } else {
          this.alertMsg(alertElem, "error-alert", "An error occurred");
        }
      })
      .catch(err => {
        console.log(err);
      });
  }

  render() {
    return (
      <form
        className="s-form"
        method="post"
        encType="multipart/form-data"
        onSubmit={this.onSubmit}
      >
        <p className="s-formTitle">Add Product To Store</p>
        <small id="alert" />

        <div className="input-wrapper">
          <label>Product name</label>
          <input
            type="text"
            name="productName"
            defaultValue={this.state.productName}
            onChange={this.onChange}
            onFocus={this.onFocus}
          />
        </div>

        <div className="input-wrapper">
          <label>Product price</label>
          <input
            type="text"
            name="productPrice"
            placeholder="e.g. 5000"
            defaultValue={this.state.productPrice}
            onChange={this.onChange}
            onFocus={this.onFocus}
          />
        </div>

        <div className="input-wrapper">
          <label>Preferred store menu</label>
          {this.props.store.category_one != "" && (
            <button
              className="btn s-storeMenu-options"
              key={1}
              onClick={this.getStoreMenu}
              data-name={this.props.store.category_one}
            >
              {this.props.store.category_one}
              <span className="s-storeMenu-options-mark">
                <Mark />
              </span>
            </button>
          )}

          {this.props.store.category_two != "" && (
            <button
              className="btn s-storeMenu-options"
              key={2}
              onClick={this.getStoreMenu}
              data-name={this.props.store.category_two}
            >
              {this.props.store.category_two}
              <span className="s-storeMenu-options-mark">
                <Mark />
              </span>
            </button>
          )}

          {this.props.store.category_three != "" && (
            <button
              className="btn s-storeMenu-options"
              key={3}
              onClick={this.getStoreMenu}
              data-name={this.props.store.category_three}
            >
              {this.props.store.category_three}
              <span className="s-storeMenu-options-mark">
                <Mark />
              </span>
            </button>
          )}

          {this.props.store.category_four != "" && (
            <button
              className="btn s-storeMenu-options"
              key={4}
              onClick={this.getStoreMenu}
              data-name={this.props.store.category_four}
            >
              {this.props.store.category_four}
              <span className="s-storeMenu-options-mark">
                <Mark />
              </span>
            </button>
          )}
        </div>

        <div className="input-wrapper">
          <label>Select product category</label>
          {this.state.categories.length != 0 &&
            Object.keys(
              this.state.categories[this.state.storeDetails.store_category]
            ).map((cat, i) => (
              <button
                className="btn s-catOPtions"
                key={i}
                onClick={this.getCat}
                data-name={cat}
              >
                {cat}
                <span className="s-catOPtions-mark">
                  <Mark />
                </span>
              </button>
            ))}
        </div>

        <div className="input-wrapper">
          {this.state.subCat.length != 0 && (
            <label>Select other product category</label>
          )}
          {this.state.subCat.length != 0 &&
            this.state.subCat.map((scat, i) => (
              <button
                className="btn s-scatOPtions"
                key={i}
                onClick={this.getSubCat}
                data-name={scat}
              >
                {scat}
                <span className="s-scatOPtions-mark">
                  <Mark />
                </span>
              </button>
            ))}
        </div>

        <br />
        <label style={{ marginLeft: 5 }}>Product Images</label>
        <div className="input-file s-addProduct-input-file s-addProduct-input-file-w1">
          <label htmlFor="s-addProduct-input-file-1" />
          <input
            type="file"
            id="s-addProduct-input-file-1"
            name="product_image_one"
            onChange={this.onChangeImageOne}
          />
          <img src="#" id="s-addProduct-img-1" />
          <button className="btn" id="s-addProduct-btn-1">
            Image 1
          </button>
        </div>

        <div className="input-file s-addProduct-input-file s-addProduct-input-file-w2">
          <label htmlFor="s-addProduct-input-file-2" />
          <input
            type="file"
            id="s-addProduct-input-file-2"
            name="product_image_two"
            onChange={this.onChangeImageTwo}
            disabled
          />
          <img src="#" id="s-addProduct-img-2" />
          <button className="btn" id="s-addProduct-btn-2">
            Image 2
          </button>
        </div>

        <div className="input-file s-addProduct-input-file s-addProduct-input-file-w3">
          <label htmlFor="s-addProduct-input-file-3" />
          <input
            type="file"
            id="s-addProduct-input-file-3"
            name="product_image_three"
            onChange={this.onChangeImageThree}
            disabled
          />
          <img src="#" id="s-addProduct-img-3" />
          <button className="btn" id="s-addProduct-btn-3">
            Image 3
          </button>
        </div>

        <div className="clear-float" />
        <br />
        <div className="input-wrapper">
          <label>Product details</label>
          <textarea
            rows="8"
            name="productDetails"
            defaultValue={this.state.productDetails || ""}
            onChange={this.onChange}
            onFocus={this.onFocus}
          />
        </div>
        <div className="input-wrapper">
          <input type="submit" value="Submit" className="btn s-submitbtn" />
        </div>
      </form>
    );
  }
}

export default AddProduct;
