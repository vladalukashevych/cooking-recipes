function confirmDelete() {
    if (confirm("Delete Account?"))
        location.href = 'linktoaccountdeletion';
}

function submitFavourite(recipeId) {
    let http = new XMLHttpRequest();
    http.open("POST", "/recipe/index.php", true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let params = `recipe_id=${recipeId}`;
    http.send(params);
}

function addIngredientFields(id = null, valueName = null, valueAmount = null) {
    let ingredientsList = document.getElementById('ingredients');
    let divElement = document.createElement("div");
    ingredientsList.appendChild(divElement);
    let rowHtml =
        ' <div class="row px-5 mb-2 ingredient-row" id="in">' +
        '                <input type="hidden" id="id" name="ingredient-id[]"'
    if (id != null)
        rowHtml += 'value="' + id + '">\n';
    else
        rowHtml += '>\n';

    rowHtml +=
        '                <div class="col-5">\n' +
        '                    <label for="ingredient-name" class="form-label">Name</label>\n' +
        '                    <input type="text" class="form-control sub-form-control" id="ingredient-name"\n' +
        '                           name="ingredient-name[]"\n' +
        '                           aria-describedby="ingredient-nameHelp"';
    if (valueName != null)
        rowHtml += 'value="' + valueName + '">\n';
    else
        rowHtml += '>\n';

    rowHtml +=
        '                </div>\n' +
        '                <div class="col-5">\n' +
        '                    <label for="ingredient-amount" class="form-label">Amount</label>\n' +
        '                    <input type="text" class="form-control sub-form-control" id="ingredient-amount"\n' +
        '                           name="ingredient-amount[]" aria-describedby="ingredient-amountHelp"';
    if (valueAmount != null)
        rowHtml += 'value="' + valueAmount + '">\n';
    else
        rowHtml += '>\n';

    rowHtml +=
        '                </div>' +
        '                <div class="col-1 d-flex">' +
        '                    <button type="button" class="btn btn-danger d-block align-self-end fa fa-close delete-ingredient-field" ' +
        '                       onClick="removeField(this)"><span type="button"></span></button> ' +
        '            </div>';

    divElement.outerHTML = rowHtml;
}

function removeField(minusElement) {
    if (minusElement.parentElement.parentElement.parentElement.children.length > 2)
        minusElement.parentElement.parentElement.remove();
}

function addRecipeCard(id, name, description, time, filePath = null, authorised = false, favourite = false) {
    let recipeList = document.getElementById('recipes-list');
    let recipe = document.createElement("div");
    recipeList.appendChild(recipe);

    let rowHtml =
        `<div class="row">
    <div class="col">
        <a href="/recipe/view/${id}" class="text-decoration-none text-dark">
            <div class="card mb-3 recipe ">
                <div class="row g-0">
                    <div class="col-8">
                        <div class="card-body">
                            <h5 class="card-title">${name}</h5>
                            <p class="card-text">${description}</p>
                            <p class="card-text"><small class="text-muted">Cooking time: ${time} minutes</small></p>
                        </div>
                    </div>`;
    if (filePath != null)
        rowHtml +=
            `           <div class="col-4">
                            <img src="/${filePath}" class="rounded-end list-recipe-img"
                                 alt="${name}">
                        </div>`;

    rowHtml +=
        `
                </div>
            </div>
        </a>
    </div>`;
    if (authorised)
        rowHtml += `
        <div class="col-1 pt-5">
            ${getFavouriteButtonHtml(id, favourite)}
        </div>`;
    rowHtml +=
        `</div>`;

    recipe.outerHTML = rowHtml;
}

function addFavouriteButton(id, favourite) {
    let recipeList = document.getElementById('recipe-favourite-btn');
    let recipe = document.createElement("div");
    recipeList.appendChild(recipe);
    recipe.outerHTML = getFavouriteButtonHtml(id, favourite);
}

function getFavouriteButtonHtml(id, favourite) {
    let rowHtml =
        `<form type="post" action="/recipe/index">
            <label for="check-favourite-${id}" class="favourite-label">
                <input type="checkbox" name="favourite" id="check-favourite-${id}" onchange="submitFavourite(${id})"`;
    if (favourite)
        rowHtml +=
            `checked`;

    rowHtml +=
        `
                />
                <i class="fa fa-star-o fa-3x"></i>
                <i class="fa fa-star fa-3x"></i>
            </label>
        </form>`;
    return rowHtml;
}