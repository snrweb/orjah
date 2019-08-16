import React, { Component } from "react";

class Terms extends Component {
  constructor(props) {
    super(props);

    this.state = {
      terms: ""
    };

    this.updateTerms = this.updateTerms.bind(this);
    this.submitTerms = this.submitTerms.bind(this);
  }

  updateTerms(e) {
    let terms = e.target.value;
    this.setState({ terms: terms });
  }

  submitTerms(e) {
    e.preventDefault();
    let formdata = new FormData();
    formdata.append("terms", this.state.terms);
    fetch(`${this.props.root}/editTerms`, {
      method: "POST",
      body: formdata
    })
      .then(res => res.text())
      .then(text => console.log(text))
      .catch(err => {
        console.log(err);
      });
  }

  render() {
    return (
      <form className="form" method="post">
        <p className="formTitle">Orjah Terms and Condition</p>
        <div className="input-wrapper">
          <textarea
            type="url"
            className="textarea"
            placeholder="Orjah Terms and Condition"
            name="terms"
            rows="10"
            value={this.state.terms}
            onChange={this.updateTerms}
          />
        </div>
        <div className="input-wrapper">
          <input
            type="submit"
            value="Submit"
            className="btn submitbtn"
            onClick={this.submitTerms}
          />
        </div>
      </form>
    );
  }
}

export default Terms;
