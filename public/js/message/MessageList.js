import React, { Component } from "react";
import { Link } from "react-router-dom";

import TimeDiff from "../helpers/TimeDiff";
import ChatBox from "./ChatBox";

class MessageList extends Component {
  constructor(props) {
    super(props);

    this.state = {
      chatID: "",
      isLoggedInBuyer: false,
      isLoggedInStore: false,
      messages: [],
      loadCmp: true
    };

    this.loadCmp = this.loadCmp.bind(this);
    this.loadMobileCmp = this.loadMobileCmp.bind(this);
    this.showChatBox = this.showChatBox.bind(this);
  }

  componentDidMount() {
    fetch(`${this.props.root}message`)
      .then(res => res.json())
      .then(data => this.setState({ messages: data.messages }))
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

    document.body.style.background = "white";
  }

  showChatBox(e) {
    let chatID = e.target.getAttribute("data-uid");
    this.setState({ chatID: chatID });
  }

  loadCmp() {
    if (this.state.loadCmp == true) {
      return (
        <ul className="page-message pull-left">
          {this.state.isLoggedInStore &&
            this.state.messages.map(msg => (
              <li onClick={this.showChatBox} data-uid={msg.unique_id}>
                <span data-uid={msg.unique_id}>{msg.buyer_name}</span>
                <p data-uid={msg.unique_id}>{msg.message}</p>
                <span
                  data-uid={msg.unique_id}
                  style={{ fontWeight: 500, fontSize: 13 + "px" }}
                >
                  <TimeDiff date={msg.created_at} />
                </span>
              </li>
            ))}

          {this.state.isLoggedInBuyer &&
            this.state.messages.map(msg => (
              <li onClick={this.showChatBox} data-uid={msg.unique_id}>
                <span data-uid={msg.unique_id}>{msg.store_name}</span>
                <p data-uid={msg.unique_id}>{msg.message}</p>
                <span
                  data-uid={msg.unique_id}
                  style={{ fontWeight: 500, fontSize: 13 + "px" }}
                >
                  <TimeDiff date={msg.created_at} />
                </span>
              </li>
            ))}
        </ul>
      );
    }
  }

  loadMobileCmp() {
    if (this.state.loadCmp == true) {
      return (
        <ul className="page-message pull-left">
          {this.state.isLoggedInStore &&
            this.state.messages.map(msg => (
              <Link to={`/message/read/${msg.unique_id} `} key={msg.message_id}>
                <li>
                  <span>{msg.buyer_name}</span>
                  <p>{msg.message}</p>
                  <span style={{ fontWeight: 500, fontSize: 13 + "px" }}>
                    <TimeDiff date={msg.created_at} />
                  </span>
                </li>
              </Link>
            ))}

          {this.state.isLoggedInBuyer &&
            this.state.messages.map(msg => (
              <Link to={`/message/read/${msg.unique_id} `} key={msg.message_id}>
                <li>
                  <span>{msg.store_name}</span>
                  <p>{msg.message}</p>
                  <span style={{ fontWeight: 500, fontSize: 13 + "px" }}>
                    <TimeDiff date={msg.created_at} />
                  </span>
                </li>
              </Link>
            ))}
        </ul>
      );
    }
  }

  render() {
    return (
      <React.Fragment>
        {window.innerWidth < 767 ? this.loadMobileCmp() : this.loadCmp()}

        {this.state.chatID != "" && (
          <ChatBox
            isLoggedInBuyer={this.state.isLoggedInBuyer}
            isLoggedInStore={this.state.isLoggedInStore}
            root={this.props.root}
            uniqueId={this.state.chatID}
          />
        )}
      </React.Fragment>
    );
  }
}

export default MessageList;
