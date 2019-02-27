class Gallery {
  constructor(evt) {
    this.wrapperClass = 'galleryWrapper';
    this.dataId = evt.target.dataset.id;
    this.imgSrc = evt.target.src;
    this.imgAlt = evt.target.alt;
    this.views = (evt.target.dataset.views === 'null') ? 0 : evt.target.dataset.views;
    this._render();
    this._updateViews(evt);
  }

  _render() {
    let $wrapper = $(`<div class='${this.wrapperClass}'></div>`);
    let $title = $(`<h1>${this.imgAlt}</h1>`);
    let $info = $(`<p>Количество просмотров: ${this.views}</p>`);
    let $remBtn = $(`<button>X</button>`);
    $remBtn.click(evt => {
      this._remove(evt);
      location.reload();
    });
    let $img = $(`<img src="${this.imgSrc}" alt="${this.imgAlt}">`);
    $wrapper.append($title);
    $wrapper.append($info);
    $wrapper.append($img);
    $wrapper.append($remBtn);
    $('body').append($wrapper);
  }

  _remove(evt) {
    evt.preventDefault();
    $(`.${this.wrapperClass}`).remove();
  }

  _updateViews() {
    $.get(`index.php/?id=${this.dataId}`);
  }
}