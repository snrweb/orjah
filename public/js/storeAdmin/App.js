import React, { Component } from "react";
import { BrowserRouter as Router, Route } from "react-router-dom";

import NavBarWhite from "../components/NavBarWhite";

import { ROOT, UROOT } from "../config/config";
import StoreAdmin from "./StoreAdmin";
import About from "../storePages/About";
import Contact from "../storePages/Contact";
import DeliveryTerms from "../storePages/DeliveryTerms";
import ReturnPolicy from "../storePages/ReturnPolicy";

class App extends Component {
  constructor(props) {
    super(props);

    this.state = {
      isLoggedInStore: false,
      isLoggedInBuyer: false,
      storeName: "",
      storeDetails: {},
      totalProductInBasket: 0,
      totalOrders: 0,
      msgCount: 0,
      newOrders: 0
    };
  }

  componentDidMount() {
    fetch(`${ROOT}whoIsLoggedIn`)
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

    fetch(`${ROOT}stores/admin`)
      .then(res => res.json())
      .then(res => {
        this.setState({
          storeID: res.storeId,
          storeName: res.store_name,
          storeDetails: res.store_details,
          totalProductInBasket: res.totalProductInBasket,
          totalOrders: res.totalOrders,
          msgCount: res.msgCount,
          newOrders: res.newOrders
        });
      })
      .catch(err => {
        console.log(err);
      });
  }

  render() {
    return (
      <Router basename="/orjah/">
        <NavBarWhite
          totalProductInBasket={this.state.totalProductInBasket}
          totalOrders={this.state.totalOrders}
          msgCount={this.state.msgCount}
          newOrders={this.state.newOrders}
          root={ROOT}
          uroot={UROOT}
        />

        {this.state.isLoggedInStore && (
          <Route
            path="/stores/admin"
            render={props => (
              <StoreAdmin {...props} root={ROOT} uroot={UROOT} />
            )}
          />
        )}

        <Route
          path="/:storeName/about"
          render={props => (
            <About
              {...props}
              root={ROOT}
              uroot={UROOT}
              storeName={this.state.storeName}
              storeAbout={this.state.storeDetails.store_about}
            />
          )}
        />

        <Route
          path="/:storeName/contact"
          render={props => <Contact {...props} root={ROOT} uroot={UROOT} />}
        />

        <Route
          path="/:storeName/delivery"
          render={props => (
            <DeliveryTerms
              {...props}
              root={ROOT}
              uroot={UROOT}
              storeName={this.state.storeName}
              deliveryTerms={this.state.storeDetails.delivery_terms}
            />
          )}
        />
        <Route
          path="/:storeName/return"
          render={props => (
            <ReturnPolicy
              {...props}
              root={ROOT}
              uroot={UROOT}
              returnPolicy={this.state.storeDetails.return_policy}
            />
          )}
        />
      </Router>
    );
  }
}

export default App;
