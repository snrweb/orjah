import React, { Component } from "react";
import { BrowserRouter as Router, Route } from "react-router-dom";

import NavBarWhite from "../components/NavBarWhite";

import { ROOT, UROOT } from "../config/config";
import About from "../storePages/About";
import Contact from "../storePages/Contact";
import DeliveryTerms from "../storePages/DeliveryTerms";
import ReturnPolicy from "../storePages/ReturnPolicy";
import MessageList from "../message/MessageList";
import Store from "../storeView/Store";
import Basket from "../buyerPages/Basket";
import Orders from "../buyerPages/Orders";
import ChatBox from "../message/ChatBox";

class App extends Component {
  constructor(props) {
    super(props);

    this.state = {
      isLoggedInBuyer: false,
      isLoggedInStore: false,
      storeName: "",
      storeDetails: {},
      storeMenus: [],
      products: [],
      path: ""
    };

    this.setStore = this.setStore.bind(this);
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

    let storeName;
    let path = location.pathname.split("/");

    if (path[1] == "orjah") {
      storeName = path[2];
    } else {
      storeName = path[1];
    }
    this.setState({ path: storeName });

    if (["message", "order", "basket"].indexOf(storeName) < 0) {
      fetch(`${ROOT}${storeName}`)
        .then(res => res.json())
        .then(res => {
          this.setState({
            storeName: res.storeName,
            storeDetails: res.storeDetails,
            storeMenus: res.storeMenus,
            products: res.products
          });
        })
        .catch(err => {
          console.log(err);
        });
    }
    window.addEventListener("popstate", this.setStore);
  }

  setStore() {
    let storeName;
    let path = location.pathname.split("/");

    if (path[1] == "orjah") {
      storeName = path[2];
    } else {
      storeName = path[1];
    }

    this.setState({ path: storeName });

    if (["message", "order", "basket"].indexOf(storeName) < 0) {
      fetch(`${ROOT}${storeName}`)
        .then(res => res.json())
        .then(res => {
          this.setState({
            storeName: res.storeName,
            storeDetails: res.storeDetails,
            storeMenus: res.storeMenus,
            products: res.products
          });
        })
        .catch(err => {
          console.log(err);
        });
    }
  }

  render() {
    return (
      <Router basename="/orjah/">
        <NavBarWhite root={ROOT} uroot={UROOT} />

        {["message", "order", "basket"].indexOf(this.state.path) < 0 && (
          <Route
            exact
            path={`/${this.state.path}`}
            render={() => (
              <Store
                root={ROOT}
                uroot={UROOT}
                storeName={this.state.storeName}
                storeDetails={this.state.storeDetails}
                storeMenus={this.state.storeMenus}
                products={this.state.products}
              />
            )}
          />
        )}

        <Route
          path={`/:storeName/about`}
          render={props => <About {...props} root={ROOT} uroot={UROOT} />}
        />

        <Route
          path={`/:storeName/contact`}
          render={props => <Contact {...props} root={ROOT} uroot={UROOT} />}
        />

        <Route
          path={`/:storeName/delivery`}
          render={props => (
            <DeliveryTerms {...props} root={ROOT} uroot={UROOT} />
          )}
        />

        <Route
          path={`/:storeName/return`}
          render={props => (
            <ReturnPolicy {...props} root={ROOT} uroot={UROOT} />
          )}
        />

        {(this.state.isLoggedInBuyer || this.state.isLoggedInStore) && (
          <Route>
            <Route
              exact
              path="/message"
              render={() => <MessageList root={ROOT} />}
            />
            <Route
              path="/message/read/:uniqueId"
              render={props => (
                <ChatBox
                  {...props}
                  root={ROOT}
                  isLoggedInBuyer={this.state.isLoggedInBuyer}
                  isLoggedInStore={this.state.isLoggedInStore}
                />
              )}
            />
          </Route>
        )}

        <Route
          exact
          path="/basket"
          render={() => <Basket root={ROOT} uroot={UROOT} />}
        />

        <Route
          exact
          path="/order"
          render={() => <Orders root={ROOT} uroot={UROOT} />}
        />
      </Router>
    );
  }
}

export default App;
