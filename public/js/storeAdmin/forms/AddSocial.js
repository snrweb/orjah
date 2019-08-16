import React, { Component } from "react";

class AddSocial extends Component {
  constructor(props) {
    super(props);
    this.state = {
      facebook: this.props.facebook,
      twitter: this.props.twitter,
      instagram: this.props.instagram
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
    formdata.append("facebook", this.state.facebook);
    formdata.append("twitter", this.state.twitter);
    formdata.append("instagram", this.state.instagram);

    fetch(`${this.props.root}stores/admin/editSocial`, {
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
            "Store social links update successful"
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
        <p className="s-formTitle">Social Media Links</p>
        <small id="alert" />
        <div className="input-wrapper">
          <label>Facebook page</label>
          <input
            type="url"
            placeholder="Facebook page link"
            className="input"
            name="facebook"
            defaultValue={this.state.facebook}
            onChange={this.onChange}
            onFocus={this.onFocus}
          />
        </div>
        <div className="input-wrapper">
          <label>Twitter account</label>
          <input
            type="url"
            placeholder="Twitter account link"
            className="input"
            name="twitter"
            defaultValue={this.state.twitter}
            onChange={this.onChange}
            onFocus={this.onFocus}
          />
        </div>
        <div className="input-wrapper">
          <label>Instagram page</label>
          <input
            type="url"
            placeholder="Instagram account link"
            className="input"
            name="instagram"
            defaultValue={this.state.instagram}
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

export default AddSocial;
