import {UI} from "@typo3/ckeditor5-bundle.js";
import CheckboxView from "../ui/checkboxview.js";
import TextareaView from "../ui/textareaview.js";

export default class Email4LinkView extends UI.View {
  constructor(locale) {
    super(locale);

    this.titleInputView = this._createInput('Title');
    this.descriptionInputView = this._createTextarea('Description');
    this.checkboxInputView = this._createCheckbox('Use title as description');
    this.saveButtonView = this._createButton(
      'Save',
      '<svg clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m2.25 12.321 7.27 6.491c.143.127.321.19.499.19.206 0 .41-.084.559-.249l11.23-12.501c.129-.143.192-.321.192-.5 0-.419-.338-.75-.749-.75-.206 0-.411.084-.559.249l-10.731 11.945-6.711-5.994c-.144-.127-.322-.19-.5-.19-.417 0-.75.336-.75.749 0 .206.084.412.25.56" fill-rule="nonzero"/></svg>',
      'ck-button-save'
    );
    this.saveButtonView.type = 'submit';
    this.cancelButtonView = this._createButton(
      'Cancel',
      '<svg clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m12 10.93 5.719-5.72c.146-.146.339-.219.531-.219.404 0 .75.324.75.749 0 .193-.073.385-.219.532l-5.72 5.719 5.719 5.719c.147.147.22.339.22.531 0 .427-.349.75-.75.75-.192 0-.385-.073-.531-.219l-5.719-5.719-5.719 5.719c-.146.146-.339.219-.531.219-.401 0-.75-.323-.75-.75 0-.192.073-.384.22-.531l5.719-5.719-5.72-5.719c-.146-.147-.219-.339-.219-.532 0-.425.346-.749.75-.749.192 0 .385.073.531.219z"/></svg>',
      'ck-button-cancel'
    );

    this.cancelButtonView.delegate('execute').to(this, 'cancel');

    this.childViews = this.createCollection([
      this.titleInputView,
      this.descriptionInputView,
      this.checkboxInputView,
      this.saveButtonView,
      this.cancelButtonView
    ]);

    this.setTemplate({
      tag: 'form',
      attributes: {
        class: ['ck', 'ck-email4link-form'],
        tabindex: '-1'
      },
      children: [
        this.titleInputView,
        this.descriptionInputView,
        this.checkboxInputView,
        {
          tag: 'p',
          id: 'poweredbylux',
          children: [
            'yy',
            {
              tag: 'a',
              href: 'https://www.in2code.de/produkte/lux-typo3-marketing-automation/',
              target: '_blank',
              rel: 'noopener',
              style: 'font-weight:bold;text-decoration:underline;cursor:pointer;',
              children: ['xx']
            }
          ]
        },
        this.saveButtonView,
        this.cancelButtonView
      ],
    });
  }

  render() {
    super.render();

    UI.submitHandler({
      view: this
    });
  }

  focus() {
    this.childViews.first.focus();
  }

  _createInput(label) {
    const labeledInput = new UI.LabeledFieldView(this.locale, UI.createLabeledInputText);
    labeledInput.label = label;
    return labeledInput;
  }

  _createButton(label, icon, className) {
    const button = new UI.ButtonView();
    button.set({
      label,
      icon,
      tooltip: true,
      class: className
    });
    return button;
  }

  _createCheckbox(label) {
    const checkbox = new CheckboxView(this.locale);
    checkbox.set({
      label
    });
    return checkbox;
  }

  _createTextarea(label) {
    const textarea = new TextareaView(this.locale);
    textarea.set({
      label
    });
    return textarea;
  }
}
