import React, { Component } from "react";
import { Link } from "react-router-dom";

import Logo from "./../../images/logo/logo.jpg";
import SearchSvg from "./../../images/svg/search.svg";
import MenuSvg from "./../../images/svg/menu.svg";
import ShopSvg from "./../../images/svg/shop.svg";
import ListSvg from "./../../images/svg/list.svg";
import MessageSvg from "./../../images/svg/message.svg";
import CartSvg from "./../../images/svg/cart.svg";

class NavBarWhite extends Component {
  constructor(props) {
    super(props);

    this.state = {
      isLoggedInBuyer: false,
      isLoggedInStore: false,
      totalProductInBasket: 0,
      totalOrders: 0,
      msgCount: 0,
      newOrders: 0
    };
  }

  componentDidMount() {
    fetch(`${this.props.root}`)
      .then(res => res.json())
      .then(data => {
        this.setState({
          totalProductInBasket: data.totalProductInBasket,
          totalOrders: data.totalOrders,
          msgCount: data.msgCount,
          newOrders: data.newOrders
        });
      })
      .catch(err => {
        console.log(err);
      });

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
  }

  render() {
    const logoImg = {
      backgroundImage: `url(${this.props.uroot}public/js/dist${Logo})` //`url(${Logo})`
    };
    return (
      <nav className="navBar2">
        <a href={`${this.props.uroot}`}>
          <div className="navBarLogo" style={logoImg} />
        </a>

        <div className="navBarSearch">
          <input
            type="text"
            placeholder="Search categories, products, stores..."
          />
        </div>

        <div className="navBarSearch-icon">
          <SearchSvg />
        </div>

        {this.state.isLoggedInStore ? (
          <Link to="/stores/admin/menu">
            <div className="navBarMenu-icon">
              <MenuSvg />
            </div>
          </Link>
        ) : (
          ""
        )}

        {this.state.isLoggedInStore ? (
          <React.Fragment>
            <a href={`${this.props.uroot}stores/admin`}>
              <div className="navBarAdmin">
                <ShopSvg />
              </div>
            </a>
            <Link to="/stores/admin/orders">
              <div className="navBarOrders">
                <ListSvg />
                <span id="navBarOrders-span"> {this.state.newOrders} </span>
              </div>
            </Link>
          </React.Fragment>
        ) : (
          ""
        )}

        {this.state.isLoggedInBuyer ? (
          <Link to="/message">
            <div className="navBarMsg">
              <MessageSvg />
              <span>{this.state.msgCount} </span>
            </div>
          </Link>
        ) : (
          ""
        )}

        {this.state.isLoggedInStore ? (
          <a href={`${this.props.uroot}message`}>
            <div className="navBarMsg">
              <MessageSvg />
              <span>{this.state.msgCount} </span>
            </div>
          </a>
        ) : (
          ""
        )}

        {this.state.isLoggedInBuyer ? (
          <React.Fragment>
            <Link to="/order">
              <div className="navBarOrders">
                <ListSvg />
                <span id="navBarOrders-span">{this.state.totalOrders}</span>
              </div>
            </Link>

            <Link to="/basket">
              <div className="navBarBasket">
                <CartSvg />
                <span id="navBarBasket-span">
                  {" "}
                  {this.state.totalProductInBasket}{" "}
                </span>
              </div>
            </Link>
          </React.Fragment>
        ) : (
          ""
        )}

        {!this.state.isLoggedInStore && !this.state.isLoggedInBuyer ? (
          <a href="/register">
            <div className="navBarRegister">
              <button className="btn">Sign up</button>
            </div>
          </a>
        ) : (
          ""
        )}

        {!this.state.isLoggedInStore && !this.state.isLoggedInBuyer ? (
          <a href="/login">
            <div className="navBarLogin">
              <button className="btn">Login</button>
            </div>
          </a>
        ) : (
          ""
        )}
      </nav>
    );
  }
}

export default NavBarWhite;
