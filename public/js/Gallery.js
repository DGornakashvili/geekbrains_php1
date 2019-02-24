class Gallery {
  constructor(evt) {
    this.wrapperClass = 'galleryWrapper';
    this.imgSrc = evt.target.src;
    this.imgAlt = evt.target.alt;
    this._render();
  }

  _render() {
    let $wrapper = $(`<div class='${this.wrapperClass}'></div>`);
    let $title = $(`<h1>${this.imgAlt}</h1>`);
    let $remBtn = $(`<button>X</button>`);
    $remBtn.click(evt => this._remove(evt));
    let $img = $(`<img src="${this.imgSrc}" alt="${this.imgAlt}">`);
    $wrapper.append($title);
    $wrapper.append($img);
    $wrapper.append($remBtn);
    $('body').append($wrapper);
  }

  _remove(evt) {
    evt.preventDefault();
    $(`.${this.wrapperClass}`).remove();
  }
}