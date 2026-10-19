class Todo {
  constructor() {
    this.storageKey = 'todo_tasks';
    this.term = '';
    this.editingId = null;
    this.tasks = [];
    this.listElement = document.getElementById('todo-list');
    this.formElement = document.getElementById('todo-form');
    this.taskInput = document.getElementById('task-input');
    this.dateInput = document.getElementById('date-input');
    this.searchInput = document.getElementById('todo-search');
    this.load();
    this.initEvents();
    this.draw();
  }

  load() {
    const saved = localStorage.getItem(this.storageKey);
    if (saved) {
      try {
        this.tasks = JSON.parse(saved);
      } catch (e) {
        this.tasks = [];
      }
    } else {
      this.tasks = [
        { id: 1, text: 'chocolate', date: '2026-11-01' },
        { id: 2, text: 'macaroon', date: '' },
        { id: 3, text: 'chupa chups', date: '' },
        { id: 4, text: 'candy canes', date: '2026-12-24' },
        { id: 5, text: 'bon bons', date: '' }
      ];
      this.save();
    }
  }

  save() {
    localStorage.setItem(this.storageKey, JSON.stringify(this.tasks));
  }

  validate(text, dateStr) {
    const trimmed = text.trim();
    if (trimmed.length < 3 || trimmed.length > 255) {
      alert('Treść zadania musi mieć od 3 do 255 znaków.');
      return false;
    }

    if (dateStr) {
      const today = new Date();
      today.setHours(0, 0, 0, 0);

      const chosenDate = new Date(dateStr);

      if (chosenDate < today) {
        alert('Data terminu musi być pusta lub w przyszłości.');
        return false;
      }
    }
    return true;
  }

  add(text, date = '') {
    if (!this.validate(text, date)) return false;

    const newTask = {
      id: Date.now(),
      text: text.trim(),
      date: date
    };

    this.tasks.push(newTask);
    this.save();
    this.draw();
    return true;
  }

  remove(id) {
    this.tasks = this.tasks.filter(task => task.id !== Number(id));
    this.save();
    this.draw();
  }

  update(id, newText, newDate) {
    if (!this.validate(newText, newDate)) return false;
    const task = this.tasks.find(t => t.id === Number(id));
    if (task) {
      task.text = newText.trim();
      task.date = newDate;
      this.editingId = null;
      this.save();
      this.draw();
      return true;
    }
    return false;
  }

  get filteredTasks() {
    if (!this.term || this.term.trim().length < 2) {
      return this.tasks;
    }
    const cleanTerm = this.term.trim().toLowerCase();

    return this.tasks.filter(task =>
      task.text.toLowerCase().includes(cleanTerm)
    );
  }

  highlightMatch(text) {
    if (!this.term || this.term.trim().length < 2) {
      return text;
    }

    const regex = new RegExp(`(${this.term.trim()})`, 'gi');
    return text.replace(regex, '<mark>$1</mark>');
  }

  draw() {
    this.listElement.innerHTML = '';
    const tasksToDisplay = this.filteredTasks;
    tasksToDisplay.forEach(task => {
      const li = document.createElement('li');
      li.className = 'todo-item';
      li.dataset.id = task.id;
      if (this.editingId === task.id) {
        li.classList.add('editing');
        li.innerHTML = `
          <input type="checkbox" class="todo-checkbox">
          <input type="text" class="edit-text-input" value="${task.text}" minlength="3" maxlength="255">
          <input type="date" class="edit-date-input" value="${task.date}">
          <button type="button" class="btn-save-inline">Zapisz</button>
          <button type="button" class="btn-delete" title="Usuń zadanie">🗑</button>
        `;

        const editTextInput = li.querySelector('.edit-text-input');
        const editDateInput = li.querySelector('.edit-date-input');
        const btnSave = li.querySelector('.btn-save-inline');

        btnSave.addEventListener('click', (e) => {
          e.stopPropagation();
          this.update(task.id, editTextInput.value, editDateInput.value);
        });

        editTextInput.addEventListener('keydown', (e) => {
          if (e.key === 'Enter') {
            this.update(task.id, editTextInput.value, editDateInput.value);
          }
        });

      } else {
        li.innerHTML = `
          <input type="checkbox" class="todo-checkbox">
          <span class="todo-text">${this.highlightMatch(task.text)}</span>
          <span class="todo-date">${task.date || ''}</span>
          <button type="button" class="btn-delete" title="Usuń zadanie">🗑</button>
        `;
        const textSpan = li.querySelector('.todo-text');
        const dateSpan = li.querySelector('.todo-date');

        const startEdit = (e) => {
          e.stopPropagation();
          this.editingId = task.id;
          this.draw();
        };

        textSpan.addEventListener('click', startEdit);
        dateSpan.addEventListener('click', startEdit);
      }

      const deleteBtn = li.querySelector('.btn-delete');
      deleteBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        this.remove(task.id);
      });

      this.listElement.appendChild(li);
    });
  }

  initEvents() {
    this.formElement.addEventListener('submit', (e) => {
      e.preventDefault();
      const text = this.taskInput.value;
      const date = this.dateInput.value;

      if (this.add(text, date)) {
        this.taskInput.value = '';
        this.dateInput.value = '';
      }
    });

    this.searchInput.addEventListener('input', (e) => {
      this.term = e.target.value;
      this.draw();
    });

    document.addEventListener('click', (e) => {
      if (this.editingId !== null) {
        const editingLi = this.listElement.querySelector(`li[data-id="${this.editingId}"]`);
        if (editingLi && !editingLi.contains(e.target)) {
          const editTextInput = editingLi.querySelector('.edit-text-input');
          const editDateInput = editingLi.querySelector('.edit-date-input');
          if (editTextInput && editDateInput) {
            this.update(this.editingId, editTextInput.value, editDateInput.value);
          }
        }
      }
    });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  document.todo = new Todo();
});
