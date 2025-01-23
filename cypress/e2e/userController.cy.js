describe('UserController Tests', () => {
  before(() => {
    Cypress.on('uncaught:exception', (err, runnable) => {
      if (err.message.includes('google is not defined')) {
        // Ignore cette erreur spécifique
        return false;
      }
      if (err.message.includes('addEventListener')) {
        // Ignore cette erreur spécifique
        return false;
      }
      // Ne pas ignorer les autres erreurs
      return true;
    });
  });

  beforeEach(() => {
    // Se connecter avant chaque test
    cy.login('mael@morin.fr', 'toto');
    cy.visit('/user');  // Accède à la page de gestion des utilisateurs après la connexion
  });

  it('should display the list of users', () => {
    // Vérifie que la page d'index affiche les utilisateurs
    cy.contains('Liste des utilisateurs').should('be.visible');
    cy.get('table tbody tr').should('have.length.greaterThan', 0); // Vérifie qu'il y a au moins un utilisateur
    cy.contains('Mael').should('be.visible'); // Vérifie la présence d'un utilisateur spécifique
  });

  it('should create a new user', () => {
    // Accéder à la page de création
    cy.contains('Créer un utilisateur').click();

    // Remplir le formulaire
    cy.get('#user_nom').type('User_test');
    cy.get('#user_prenom').type('User_test');
    cy.get('#user_matricule').type('MATTEST');
    cy.get('#user_email').type('user@test.com');
    cy.get('#user_password').type('securepassword123');
    cy.get('#user_passwordConfirm').type('securepassword123');
    cy.get('#user_rue').type('123 rue du test');
    cy.get('#user_cp').type('37000');
    cy.get('#user_ville').type('Tours');
    // Wait for the city search to complete and select the city
    //cy.wait(2000); // Adjust the wait time as needed
    //cy.get('#ui-id-1').first().click(); // Adjust the selector to match the search result

    // Verify that latitude and longitude are auto-filled
    //cy.get('#user_latitude').should('not.be.empty');
    //cy.get('#user_longitude').should('not.be.empty');
    cy.get('#user_roles').find('input[type="checkbox"]').first().check();
    cy.contains('Sauvegarder').click();

    // Vérifier la redirection et l'ajout
    cy.url().should('include', '/user');
    cy.contains('User_test').should('be.visible');
  });

  it('should edit an existing user', () => {
    // Cliquer sur le bouton d'édition
    cy.get('table tbody tr')
        .contains('User_test')
        .parent()
        .find('a[href*="/edit"]')
        .should('be.visible')
        .click();

    // Modifier le formulaire
    cy.get('#user_nom').clear().type('User_modif');
    cy.get('form').submit();

    // Vérifier la mise à jour
    cy.url().should('include', '/user');
    cy.contains('User_modif').should('be.visible');
  });

  it('should delete a user', () => {
    // Trouver et cliquer sur le bouton de suppression
    cy.get('table tbody tr')
        .contains('User_test')
        .parent()
        .find('button i.fa-trash')
        .should('be.visible')
        .click();

    // Vérifier que l'utilisateur a été supprimé
    cy.url().should('include', '/user');
    cy.contains('User_modif').should('not.exist');
  });
});
