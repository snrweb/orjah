import React, { Component } from "react";
import {
  searchForStore,
  changeVisibility,
  deleteStore,
  sendMessage
} from "../controllers/storeController";
import Logo from "./../../../images/svg/small123.svg";

class StoreTable extends Component {
  constructor(props) {
    super(props);

    this.state = {
      stores: []
    };

    this.searchStore = this.searchStore.bind(this);
    this.toggleVisibility = this.toggleVisibility.bind(this);
    this.delete = this.delete.bind(this);
    this.sendMessage = this.sendMessage.bind(this);
    this.toggleSendMessageForm = this.toggleSendMessageForm.bind(this);
  }

  searchStore(e) {
    let searchValue = e.target.value;
    searchForStore(`${this.props.root}searchStore/${searchValue}`).then(res => {
      this.setState({
        stores: res
      });
    });
  }

  toggleVisibility(store_id) {
    let btn = document.querySelector(`#softDelete${store_id}`);
    let visibilityType = btn.innerHTML == "Off" ? 0 : 1;
    changeVisibility(
      `${this.props.root}softDelete/${store_id}/${visibilityType}`
    ).then(res => {
      if (res == 1) {
        btn.classList.remove("a-tableVisibiltyOn");
        btn.classList.add("a-tableVisibiltyOff");
        btn.innerHTML = "Off";
      } else {
        btn.classList.remove("a-tableVisibiltyOff");
        btn.classList.add("a-tableVisibiltyOn");
        btn.innerHTML = "On";
      }
    });
  }

  delete(store_id) {
    if (confirm("Are you sure you want to delete this store?")) {
      deleteStore(`${this.props.root}deleteStore/${store_id}`).then(res => {
        if (res) {
          document.querySelector(`#row${store_id}`).style.display = "none";
        }
      });
    }
  }

  toggleSendMessageForm(storeId) {
    document
      .querySelector("#a-sendMessageForm")
      .classList.toggle("show-modal-form");
    document
      .querySelector("#a-sendMessageForm-btn")
      .setAttribute("data-store-id", storeId);
    document.querySelector("#a-sendMessageForm-alert").innerHTML = "";
  }

  sendMessage(e) {
    e.preventDefault();
    let subject = document
      .querySelector("#a-sendMessageForm-subject")
      .value.trim();
    let message = document
      .querySelector("#a-sendMessageForm-textarea")
      .value.trim();
    let storeId = e.target.getAttribute("data-store-id").trim();

    if (subject !== "" || message !== "" || storeId !== "") {
      let alertBtn = document.querySelector("#a-sendMessageForm-alert");
      alertBtn.innerHTML = "Sending...";
      let data = [subject, message];
      sendMessage(`${this.props.root}sendMessage/${storeId}`, data).then(
        res => {
          if (res == "true") {
            alertBtn.innerHTML = "Message sent!";
          } else {
            alertBtn.innerHTML = "Message failed!";
            alertBtn.style.color = "red";
          }
        }
      );
    }
  }

  render() {
    return (
      <React.Fragment>
        <section>
          <div className="a-search-input">
            <input
              type="text"
              placeholder="Search stores by name or ID"
              onChange={this.searchStore}
            />
          </div>
          <table className="a-table">
            <thead>
              <tr>
                <td>Store Name</td>
                <td>Category</td>
                <td>Email</td>
                <td>Phone</td>
                <td>Location</td>
                <td>Rating</td>
                <td>About</td>
                <td>Visibility</td>
                <td>Delete</td>
                <td>Send Email</td>
              </tr>
            </thead>
            <tbody>
              {this.state.stores == ""
                ? this.props.stores.map(s => (
                    <tr key={s.store_id} id={`row${s.store_id}`}>
                      <td>{s.store_name}</td>
                      <td>{s.store_category}</td>
                      <td>{s.store_email}</td>
                      <td>{s.store_phone}</td>
                      <td>{`${s.store_street}, ${s.store_city}, ${
                        s.store_country
                      }`}</td>
                      <td>{s.store_rating}</td>
                      <td>{s.store_about}</td>
                      <td>
                        <button
                          className={
                            s.deleted == 0
                              ? `btn a-tableVisibiltyOn`
                              : `btn a-tableVisibiltyOff`
                          }
                          id={`softDelete${s.store_id}`}
                          onClick={() => this.toggleVisibility(s.store_id)}
                        >
                          {s.deleted == 0 ? `On` : `Off`}
                        </button>
                      </td>

                      <td>
                        <button
                          className="btn a-tableDelete"
                          id={`delete${s.store_id}`}
                          onClick={() => this.delete(s.store_id)}
                        >
                          Delete
                        </button>
                      </td>
                      <td>
                        <button
                          className="btn a-tableSendMsg"
                          id={`sendMsg${s.store_id}`}
                          onClick={() => this.toggleSendMessageForm(s.store_id)}
                        >
                          <Logo />
                        </button>
                      </td>
                    </tr>
                  ))
                : this.state.stores.map(s => (
                    <tr key={s.store_id} id={`row${s.store_id}`}>
                      <td>{s.store_name}</td>
                      <td>{s.store_category}</td>
                      <td>{s.store_email}</td>
                      <td>{s.store_phone}</td>
                      <td>{`${s.store_street}, ${s.store_city}, ${
                        s.store_country
                      }`}</td>
                      <td>{s.store_rating}</td>
                      <td>{s.store_about}</td>
                      <td>
                        <button
                          className={
                            s.deleted == 0
                              ? `btn a-tableVisibiltyOn`
                              : `btn a-tableVisibiltyOff`
                          }
                          id={`softDelete${s.store_id}`}
                          onClick={() => this.toggleVisibility(s.store_id)}
                        >
                          {s.deleted == 0 ? `On` : `Off`}
                        </button>
                      </td>

                      <td>
                        <button
                          className="btn a-tableDelete"
                          id={`delete${s.store_id}`}
                          onClick={() => this.delete(s.store_id)}
                        >
                          Delete
                        </button>
                      </td>
                      <td>
                        <button
                          className="btn a-tableSendMsg"
                          id={`sendMsg${s.store_id}`}
                          onClick={() => this.toggleSendMessageForm(s.store_id)}
                        >
                          <Logo />
                        </button>
                      </td>
                    </tr>
                  ))}
            </tbody>
          </table>
        </section>

        <form
          className="a-sendMessageForm"
          id="a-sendMessageForm"
          method="post"
          encType="multipart/form-data"
        >
          <div className="editor">
            <div className="hide-editor" onClick={this.toggleSendMessageForm}>
              X
            </div>
            <div>
              <input
                type="text"
                className="a-sendMessageForm-subject"
                id="a-sendMessageForm-subject"
                placeholder="Subject"
              />
            </div>
            <textarea
              rows="10"
              className="a-sendMessageForm-textarea"
              id="a-sendMessageForm-textarea"
              placeholder="Type message here..."
            />
            <button
              className="btn a-sendMessageForm-btn"
              id="a-sendMessageForm-btn"
              type="submit"
              onClick={this.sendMessage}
            >
              Send Email
            </button>
            <button
              className="btn a-sendMessageForm-alert"
              id="a-sendMessageForm-alert"
            />
          </div>
        </form>
      </React.Fragment>
    );
  }
}

export default StoreTable;
