import React, { Component } from "react";

class AboutStore extends Component {
  constructor(props) {
    super(props);
    this.state = {
      aboutStore: ""
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
    this.setState({ [e.target.name]: e.target.value });
  }

  onSubmit(e) {
    e.preventDefault();
    let alertElem = document.querySelector("#alert");

    let formdata = new FormData();
    formdata.append("store_about", this.state.aboutStore);

    fetch(`${this.props.root}stores/admin/editAbout`, {
      method: "post",
      body: formdata
    })
      .then(res => res.text())
      .then(text => {
        if (text == "true") {
          this.props.resetState();
          this.alertMsg(alertElem, "success-alert", "Store update successful");
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
        <p className="s-formTitle">About Store Update</p>
        <small id="alert" />

        <div className="input-wrapper">
          <textarea
            onChange={this.onChange}
            onFocus={this.onFocus}
            className="textarea"
            placeholder="About store"
            name="aboutStore"
            rows="10"
            defaultValue={this.props.about}
          />
        </div>
        <div className="input-wrapper">
          <input type="submit" value="Submit" className="btn s-submitbtn" />
        </div>
      </form>
    );
  }
}

export default AboutStore;
