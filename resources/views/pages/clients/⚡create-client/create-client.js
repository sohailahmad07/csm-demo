let timeout = null;


const validateGroupName = (input) => {
    let errorElement = input.closest('[data-flux-field]').querySelector('[data-error]');
    if (this.groupName.trim() === '') {
        input.setAttribute('data-invalid', '')
        errorElement.innerText = 'Please enter a group name'
        restError(input, errorElement);
        return true;
    }
    if (this.groupOrder.includes(this.groupName) && this.groupName !== oldName) {
        input.setAttribute('data-invalid', '')
        errorElement.innerText = 'This group already exists'
        restError(input, errorElement);
        return true;
    }
    return false
}

const restError = (input, errorElement) => {
    clearTimeout(timeout)
    timeout = setTimeout(() => {
        input.removeAttribute('data-invalid', '')
        errorElement.innerText = ''
    }, 3000)
}


this.$js.handleGroupSort = (item, position) => {
    const from = this.groupOrder.indexOf(item);
    if (from === -1) return;
    this.groupOrder.splice(from, 1);
    queueMicrotask(() => {
        this.groupOrder.splice(position, 0, item);
    })
}

this.$js.handleSort = (item, position, toGroup) => {
    const steps = this.steps;
    let fromGroup = null;
    let itemData = null;
    for (const group of Object.keys(steps)) {
        const index = steps[group].findIndex(step => step.id === item);
        if (index === -1) continue;
        fromGroup = group;
        itemData = {...steps[group][index]};
        const list = [...steps[group]];
        list.splice(index, 1);
        steps[fromGroup] = list;
        break;
    }
    if (!fromGroup || !itemData) return;
    const target = toGroup ?? fromGroup;
    itemData.group = target;
    const targetList = [...(steps[target] ?? [])];
    targetList.splice(position, 0, itemData);
    // fix the visual bug they happen after the alpine re-render the dom
    queueMicrotask(() => {
        steps[target] = targetList;
    })
}

this.$js.addStep = (groupName) => {
    const newStep = {
        id: crypto.randomUUID(),
        name: '',
        due_at: '',
        group: groupName,
    };
    queueMicrotask(() => {
        this.steps[groupName] = [...this.steps[groupName], newStep];
    });
}
this.$js.removeStep = (id, groupName) => {
    queueMicrotask(() => {
        this.steps[groupName] = this.steps[groupName].filter(s => s.id !== id);
    });
}

this.$js.addGroup = (input) => {
    if (validateGroupName(input)) {
        return;
    }
    const name = this.groupName;
    queueMicrotask(() => {
        this.groupOrder = [...this.groupOrder, name];
        this.steps[name] = [{
            id: crypto.randomUUID(),
            name: '',
            due_at: '',
            group: name,
        }];
    });
    Flux.modal('add-group').close()
    input.value = ''
}
let oldName;
this.$js.openUpdateModal = (groupName) => {
    oldName = groupName;
    this.groupName = groupName;
    Flux.modal('update-group').show();
}
this.$js.updateGroup = (input) => {
    if (validateGroupName(input)) {
        return;
    }
    if (this.groupName === oldName) {
        Flux.modal('update-group').close();
        return;
    }
    const newName = this.groupName;
    const index = this.groupOrder.indexOf(oldName);
    queueMicrotask(() => {
        if (index !== -1) {
            this.groupOrder = this.groupOrder.map((g, i) => i === index ? newName : g);
        }
        this.steps[newName] = this.steps[oldName].map(step => ({...step, group: newName}));
        delete this.steps[oldName];
    });
    Flux.modal('update-group').close();
}

this.$js.deleteGroup = async (name) => {
    Flux.modal('delete-group').show();
    let ok = await ask('delete-group')
    if (!ok) return;
    queueMicrotask(() => {
        this.groupOrder = this.groupOrder.filter(g => g !== name);
        delete this.steps[name];
    });
    Flux.modal('delete-group').close();
}

