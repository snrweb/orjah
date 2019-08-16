import React, { Component } from "react";
import SecondNavBar from "../components/SecondNavBar";

class ReturnPolicy extends Component {
  constructor(props) {
    super(props);

    this.state = {
      storeName: "",
      storeDetails: {},
      SecondNavBarState: false
    };
  }

  componentDidMount() {
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
        <section className="s-aboutPage">
          <p className="s-aboutPageTitle">Return Policy</p>
          <p>{this.state.storeDetails.returnPolicy}</p>
        </section>
      </React.Fragment>
    );
  }
}

export default ReturnPolicy;
