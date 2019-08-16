import React, { Component } from "react";
import SecondNavBar from "../components/SecondNavBar";

class Contact extends Component {
  constructor(props) {
    super(props);

    this.state = {
      isLoggedInBuyer: false,
      message: "",
      storeName: "",
      storeDetails: {},
      SecondNavBarState: false
    };

    this.alertMsg = this.alertMsg.bind(this);
    this.onFocus = this.onFocus.bind(this);
    this.onChange = this.onChange.bind(this);
    this.onSubmit = this.onSubmit.bind(this);
  }

  componentDidMount() {
    fetch(`${this.props.root}whoIsLoggedIn`)
      .then(res => res.json())
      .then(res => {
        if (res.whoIsLoggedIn == "buyer") {
          this.setState({ isLoggedInBuyer: true });
        } else if (res.whoIsLoggedIn == "store") {
          this.setState({ isLoggedInStore: true });
        }
      })
      .catch(err => {
        console.log(err);
      });

    fetch(`${this.props.root}${this.props.match.params.storeName}`)
      .then(res => res.json())
      .then(res => {
        this.setState({
          storeName: res.storeName,
          storeDetails: res.storeDetails
        });
      })
      .catch(err => {
        console.log(err);
      });
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

    if (!this.state.isLoggedInBuyer) {
      this.alertMsg(alertElem, "error-alert", "Please login as a buyer");
      return;
    }

    if (this.state.message.trim() == "") {
      this.alertMsg(
        alertElem,
        "error-alert",
        "Message contains empty characters"
      );
      return;
    }

    let formdata = new FormData();
    formdata.append("message", this.state.message);

    fetch(
      `${this.props.root}${this.state.storeDetails.store_name.replace(
        / /g,
        "-"
      )}/contact`,
      {
        method: "post",
        body: formdata
      }
    )
      .then(res => res.text())
      .then(data => {
        if (data == "true") {
          this.alertMsg(
            alertElem,
            "success-alert",
            "Message sent successfully"
          );
          document.querySelector("#message").value = "";
        } else {
          this.alertMsg(alertElem, "error-alert", "An error occured");
        }
      })
      .catch(err => {
        console.log(err);
      });
  }

  render() {
    return (
      <React.Fragment>
        <SecondNavBar
          categoryOne={this.state.storeDetails.category_one}
          categoryTwo={this.state.storeDetails.category_two}
          categoryThree={this.state.storeDetails.category_three}
          categoryFour={this.state.storeDetails.category_four}
          facebook={this.state.storeDetails.facebook}
          twitter={this.state.storeDetails.twitter}
          instagram={this.state.storeDetails.instagram}
          storeName={this.state.storeName}
          SecondNavBarState={this.state.SecondNavBarState}
        />
        <section
          className="s-contactPage s-container"
          style={{ boxShadow: "none" }}
        >
          <small id="alert" />
          <form method="post" onSubmit={this.onSubmit}>
            <p className="s-contactPageTitle">Get in Touch</p>
            <div>
              <textarea
                placeholder="Type your message here..."
                rows="7"
                name="message"
                id="message"
                maxLength="1000"
                onChange={this.onChange}
                onFocus={this.onFocus}
              />
            </div>
            <button type="submit" className="btn">
              Send Message
            </button>
          </form>

          <div className="s-contactPage-details">
            <p className="s-contactPageTitle">Connect with us: </p>
            <div className="s-contactPage-detail">
              <p>For support or any question</p>
              <p>
                Email us at
                <span style={{ color: "#5d49a3" }}>
                  {` ${this.state.storeDetails.store_email}`}
                </span>
              </p>
            </div>

            <div className="s-contactPage-detail">
              <p>You can also give us a call</p>
              <p>
                <span>{this.state.storeDetails.store_phone}</span>
              </p>
            </div>

            <div className="s-contactPage-detail">
              <p>
                {this.state.storeDetails.store_name +
                  " " +
                  this.state.storeDetails.store_country}
              </p>
              <p>
                {this.state.storeDetails.store_street +
                  ", " +
                  this.state.storeDetails.store_city}
              </p>
            </div>
          </div>
          <div className="clear-float" />
        </section>
      </React.Fragment>
    );
  }
}

export default Contact;
