import React, { Component } from "react";

class AddCategory extends Component {
  constructor(props) {
    super(props);
    this.state = {
      categoryOne: this.props.categoryOne,
      categoryTwo: this.props.categoryTwo,
      categoryThree: this.props.categoryThree,
      categoryFour: this.props.categoryFour
    };

    this.onFocus = this.onFocus.bind(this);
    this.onChange = this.onChange.bind(this);
    this.onSubmit = this.onSubmit.bind(this);
    this.alertMsg = this.alertMsg.bind(this);
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

  onChange(e) {
    let alertElem = document.querySelector("#alert");
    this.setState({ [e.target.name]: e.target.value });
    if (e.target.value.length > 20) {
      this.alertMsg(
        alertElem,
        "error-alert",
        "Category name must not exceed 20 characters"
      );
      return;
    }
  }

  onSubmit(e) {
    e.preventDefault();
    let alertElem = document.querySelector("#alert");

    let formdata = new FormData();
    formdata.append("category_one", this.state.categoryOne);
    formdata.append("category_two", this.state.categoryTwo);
    formdata.append("category_three", this.state.categoryThree);
    formdata.append("category_four", this.state.categoryFour);

    fetch(`${this.props.root}stores/admin/storeCategories`, {
      method: "post",
      body: formdata
    })
      .then(res => res.text())
      .then(text => {
        if (text == "true") {
          this.props.resetState();
          this.alertMsg(
            alertElem,
            "success-alert",
            "Store categories update successful"
          );
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
      <form className="s-form" method="post" onSubmit={this.onSubmit}>
        <p className="s-formTitle">Modify Categories</p>
        <small id="alert" />
        <div className="input-wrapper">
          <label>Category one</label>
          <input
            type="text"
            maxLength="20"
            name="categoryOne"
            defaultValue={this.state.categoryOne}
            placeholder="e.g New Arrivals"
            onChange={this.onChange}
            onFocus={this.onFocus}
          />
        </div>

        <div className="input-wrapper">
          <label>Category two</label>
          <input
            type="text"
            maxLength="20"
            name="categoryTwo"
            defaultValue={this.state.categoryTwo}
            onChange={this.onChange}
            onFocus={this.onFocus}
          />
        </div>

        <div className="input-wrapper">
          <label>Category three</label>
          <input
            type="text"
            maxLength="20"
            name="categoryThree"
            defaultValue={this.state.categoryThree}
            onChange={this.onChange}
            onFocus={this.onFocus}
          />
        </div>

        <div className="input-wrapper">
          <label>Category four</label>
          <input
            type="text"
            maxLength="20"
            name="categoryFour"
            defaultValue={this.state.categoryFour}
            onChange={this.onChange}
            onFocus={this.onFocus}
          />
        </div>

        <div className="input-wrapper">
          <input
            type="submit"
            value="Update Category"
            className="btn s-submitbtn"
          />
        </div>
      </form>
    );
  }
}

export default AddCategory;
