import React, { Component } from "react";
import { Route } from "react-router-dom";

import SecondNavBar from "../components/SecondNavBar";
import ColumnOne from "./component/ColumnOne";
import Menu from "./component/Menu";
import ColumnTwo from "./component/ColumnTwo";
import EditProduct from "./forms/EditProduct";
import AddProduct from "./forms/AddProduct";
import UploadLogo from "./forms/UploadLogo";
import AboutStore from "./forms/AboutStore";
import AddSocial from "./forms/AddSocial";
import EditContactDetails from "./forms/EditContactDetails";
import AddCategory from "./forms/AddCategory";
import UpdateDelivery from "./forms/UpdateDelivery";
import Orders from "./Orders";

class StoreAdmin extends Component {
  constructor(props) {
    super(props);

    this.state = {
      categories: [],
      storeName: "",
      storeDetails: {},
      storeMenu: [],
      storeVisit: [],
      storeRating: 0,
      storeBaskets: 0,
      storeBookmarks: 0,
      totalVisit: 0,
      products: []
    };

    this.resetState = this.resetState.bind(this);
    this.deleteProduct = this.deleteProduct.bind(this);
    this.changeNavBarState = this.changeNavBarState.bind(this);
  }

  componentDidMount() {
    fetch(`${this.props.root}stores/admin`)
      .then(res => res.json())
      .then(res => {
        this.setState({
          categories: res.categories,
          storeName: res.store_name,
          storeDetails: res.store_details,
          storeMenu: res.store_menus,
          storeVisit: res.store_visit,
          totalVisit: res.totalVisit,
          storeRating: res.store_rating,
          storeBaskets: res.store_baskets,
          storeBookmarks: res.store_bookmarks,
          products: res.products,
          SecondNavBarState: false
        });
      });
  }

  //cause a re-rendering when some specific forms causes a change to store details
  resetState(resetProduct = false) {
    let url = `${this.props.root}stores/resetState`;
    if (resetProduct) {
      url = `${this.props.root}stores/resetState/product`;
    }
    fetch(url)
      .then(res => res.json())
      .then(json => {
        if (resetProduct) {
          this.setState({
            storeDetails: json.store_details,
            storeName: json.storeName,
            products: json.products
          });
        } else {
          this.setState({
            storeDetails: json.store_details,
            storeName: json.storeName
          });
        }
      })
      .catch(err => {
        console.log(err);
      });
  }

  deleteProduct(e) {
    const productId = e.target.getAttribute("data-pid");
    if (confirm("Delete this product?")) {
      fetch(`${this.props.root}stores/admin/deleteProduct/${productId}`)
        .then(res => res.text())
        .then(text => {
          if (text == "true") {
            document.querySelector(`#s-product${productId}`).style.display =
              "none";
          }
        })
        .catch(err => {
          console.log(err);
        });
    }
  }

  changeNavBarState() {
    this.setState({
      SecondNavBarState: !this.state.SecondNavBarState
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
          SecondNavBarState={this.changeNavBarState}
        />

        <ColumnOne
          uroot={this.props.uroot}
          storeLogo={this.state.storeDetails.store_logo}
          storeName={this.state.storeName}
          storeVisit={this.state.storeVisit}
          totalVisit={this.state.totalVisit}
          storeBaskets={this.state.storeBaskets}
          storeBookmarks={this.state.storeBookmarks}
          storeRating={this.state.storeRating}
        />

        <div className="s-columnTwo pull-left">
          <Route
            exact
            path="/stores/admin"
            render={() => (
              <ColumnTwo
                products={this.state.products}
                storeMenus={this.state.storeMenu}
                storeName={this.state.storeName}
                storeCoverPhoto={this.state.storeDetails.store_coverPhoto}
                uroot={this.props.uroot}
                root={this.props.root}
                resetState={this.resetState}
                deleteProduct={this.deleteProduct}
                changeNavBarState={this.changeNavBarState}
              />
            )}
          />

          <Route
            path="/stores/admin/menu"
            render={() => (
              <Menu
                uroot={this.props.uroot}
                storeLogo={this.state.storeDetails.store_logo}
                storeName={this.state.storeName}
                storeVisit={this.state.storeVisit}
                totalVisit={this.state.totalVisit}
                storeBaskets={this.state.storeBaskets}
                storeBookmarks={this.state.storeBookmarks}
                storeRating={this.state.storeRating}
              />
            )}
          />

          <Route
            path="/stores/admin/orders"
            render={() => (
              <Orders
                root={this.props.root}
                resetState={this.resetState}
                storeName={this.state.storeDetails.store_name}
              />
            )}
          />

          <Route
            path="/stores/admin/editLogo"
            render={() => (
              <UploadLogo root={this.props.root} resetState={this.resetState} />
            )}
          />

          <Route
            path="/stores/admin/editAbout"
            render={() => (
              <AboutStore
                root={this.props.root}
                about={this.state.storeDetails.store_about}
                resetState={this.resetState}
              />
            )}
          />

          <Route
            path="/stores/admin/editSocial"
            render={() => (
              <AddSocial
                root={this.props.root}
                facebook={this.state.storeDetails.facebook}
                twitter={this.state.storeDetails.twitter}
                instagram={this.state.storeDetails.instagram}
                resetState={this.resetState}
              />
            )}
          />

          <Route
            path="/stores/admin/editContact"
            render={() => (
              <EditContactDetails
                root={this.props.root}
                storeName={this.state.storeDetails.store_name}
                storeEmail={this.state.storeDetails.store_email}
                storeCategory={this.state.storeDetails.store_category}
                categories={this.state.categories}
                storeCountry={this.state.storeDetails.store_country}
                storeCity={this.state.storeDetails.store_city}
                storeStreet={this.state.storeDetails.store_street}
                storePhone={this.state.storeDetails.store_phone}
                resetState={this.resetState}
              />
            )}
          />

          <Route
            path="/stores/admin/storeCategories"
            render={() => (
              <AddCategory
                root={this.props.root}
                categoryOne={this.state.storeDetails.category_one}
                categoryTwo={this.state.storeDetails.category_two}
                categoryThree={this.state.storeDetails.category_three}
                categoryFour={this.state.storeDetails.category_four}
                resetState={this.resetState}
              />
            )}
          />

          <Route
            path="/stores/admin/editDelivery"
            render={() => (
              <UpdateDelivery
                root={this.props.root}
                deliveryTerms={this.state.storeDetails.delivery_terms}
                returnPolicy={this.state.storeDetails.return_policy}
                resetState={this.resetState}
              />
            )}
          />

          <Route
            path="/stores/admin/addProduct"
            render={() => (
              <AddProduct
                root={this.props.root}
                uroot={this.props.uroot}
                resetState={this.resetState}
              />
            )}
          />

          <Route
            path="/stores/admin/modifyProduct/:productId"
            render={props => (
              <EditProduct
                {...props}
                root={this.props.root}
                uroot={this.props.uroot}
                resetState={this.resetState}
              />
            )}
          />

          <div className="clear-float" />
        </div>
      </React.Fragment>
    );
  }
}

export default StoreAdmin;
