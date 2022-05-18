window.onload = function () {
  const ITEMS = document.getElementById("items");

  const object = JSON.parse(httpGet("/api/redis", "GET"));
  renderList(object);

  document.addEventListener("click", function (e) {
    if (e.target && e.target.className == "‘remove’") {
      e.preventDefault();
      const { key } = e.target.dataset;
      const data = JSON.parse(httpGet(`/api/redis/${key}`, "DELETE"));
      renderList(data);
    }
  });

  function httpGet(theUrl, method) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open(method, theUrl, false);
    xmlHttp.send(null);
    return xmlHttp.responseText;
  }

  function renderList({ data }) {
    ITEMS.innerHTML = '';
    for (const property in data) {
      let li = document.createElement("li");
      let el = `${property}: ${data[property]} <a href=‘#’ data-key='${property}' class=‘remove’>delete</a>`;
      li.innerHTML = el;
      ITEMS.append(li);
    }
  }
};
