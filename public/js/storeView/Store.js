import React, { Component } from "react";
import SecondNavBar from "../components/SecondNavBar";

import ColumnTwo from "./component/ColumnTwo";

class Store extends Component {
  constructor(props) {
    super(props);

    this.state = {
      SecondNavBarState: true
    };
  }

  render() {
    return (
      <React.Fragment>
        <SecondNavBar
          categoryOne={this.props.storeDetails.category_one}
          categoryTwo={this.props.storeDetails.category_two}
          categoryThree={this.props.storeDetails.category_three}
          categoryFour={this.props.storeDetails.category_four}
          facebook={this.props.storeDetails.facebook}
          twitter={this.props.storeDetails.twitter}
          instagram={this.props.storeDetails.instagram}
          storeName={this.props.storeName}
          SecondNavBarState={this.state.SecondNavBarState}
        />

        <div className="s-container">
          <ColumnTwo
            products={this.props.products}
            storeMenus={this.props.storeMenus}
            storeName={this.props.storeName}
            storeCoverPhoto={this.props.storeDetails.store_coverPhoto}
            uroot={this.props.uroot}
            root={this.props.root}
          />
          <div className="clear-float" />
        </div>
      </React.Fragment>
    );
  }
}

export default Store;
