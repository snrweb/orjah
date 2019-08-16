import React, { Component } from "react";
import TimeDiff from "../helpers/TimeDiff";

class Basket extends Component {
  constructor(props) {
    super(props);
    this.state = {
      totalPrice: 0,
      basketItems: []
    };

    this.deleteBasket = this.deleteBasket.bind(this);
  }

  componentDidMount() {
    let totalPrice = 0;
    fetch(`${this.props.root}basket`)
      .then(res => res.json())
      .then(data => {
        this.setState({ basketItems: data.basketItems });
        {
          data.basketItems.forEach(b => {
            this.setState({
              totalPrice: (totalPrice += parseInt(b.product_price))
            });
          });
        }
      })
      .catch(err => {
        console.log(err);
      });

    document.body.style.background = "#f9f9f9";
  }

  deleteBasket(e) {
    let dataId = e.target.getAttribute("data-id");
    if (confirm("Delete this product from basket?")) {
      fetch(`${this.props.root}basket/remove/${dataId}`)
        .then(res => res.text())
        .then(text => {
          if (text == "true") {
            document.querySelector(`#basketItem${dataId}`).style.display =
              "none";
            let navBasketNotifElem = document.querySelector(
              "#navBarBasket-span"
            );
            let navBasketNotifValue =
              parseInt(navBasketNotifElem.innerHTML) - 1;
            navBasketNotifElem.innerHTML = navBasketNotifValue;
          }
        })
        .catch(err => {
          console.log(err);
        });
    }
  }

  render() {
    return (
      <div className="basketContainer">
        <div className="basketItems">
          {this.state.basketItems.map(b => (
            <div className="basketItem" id={`basketItem${b.basket_id}`}>
              <div className="basketItem-img">
                <img
                  src={`${this.props.uroot}public/images/products/${
                    b.product_image_one
                  }`}
                />
              </div>
              <div className="basketItem-name">
                <a
                  href={`${this.props.uroot}details/${
                    b.product_id
                  }/${b.product_name.replace(/ /g, "-")}`}
                >
                  <span>{b.product_name}</span>
                </a>
                <p>
                  <a
                    href={`${this.props.uroot}${b.store_name.replace(
                      / /g,
                      "-"
                    )} }`}
                  >
                    <span>Store: {b.store_name}</span>
                  </a>
                  <small>
                    <TimeDiff date={b.created_at} />
                  </small>
                </p>
              </div>
              <div className="basketItem-price">
                <span>NGN {b.product_price}</span>
              </div>
              <div className="basketItem-remove">
                <button
                  data-id={b.basket_id}
                  className="btn"
                  onClick={this.deleteBasket}
                >
                  Remove
                </button>
              </div>
              <div className="clear-float" />
            </div>
          ))}
        </div>

        <div className="basketCost">
          <div>
            <p className="pull-left">Total Items in Basket</p>
            <p className="pull-right">{this.state.basketItems.length}</p>
            <div className="clear-float" />
          </div>
          <div>
            <p className="pull-left">Total Cost</p>
            <p className="pull-right">NGN {this.state.totalPrice}</p>
            <div className="clear-float" />
          </div>
        </div>
        <div className="clear-float" />
      </div>
    );
  }
}

export default Basket;
