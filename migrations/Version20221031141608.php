<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221031141608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, image_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, lien VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_C0155143A76ED395 (user_id), UNIQUE INDEX UNIQ_C01551433DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, jouet_id INT DEFAULT NULL, user_id INT DEFAULT NULL, contenue LONGTEXT NOT NULL, INDEX IDX_67F068BC2E9710B0 (jouet_id), INDEX IDX_67F068BCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, lieux VARCHAR(255) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, INDEX IDX_B26681EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invitation (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, contenue VARCHAR(255) NOT NULL, INDEX IDX_F11D61A2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jouet (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, image_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, contenue LONGTEXT DEFAULT NULL, jouers INT NOT NULL, age VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, annee INT NOT NULL, cible LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', duration VARCHAR(255) NOT NULL, langues LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', code_bar VARCHAR(255) DEFAULT NULL, is_extension TINYINT(1) NOT NULL, disponible TINYINT(1) DEFAULT 1 NOT NULL, INDEX IDX_6B3DFFD8BCF5E72D (categorie_id), UNIQUE INDEX UNIQ_6B3DFFD83DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jouet_theme (jouet_id INT NOT NULL, theme_id INT NOT NULL, INDEX IDX_6F8756E72E9710B0 (jouet_id), INDEX IDX_6F8756E759027487 (theme_id), PRIMARY KEY(jouet_id, theme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jouet_mecanisme (jouet_id INT NOT NULL, mecanisme_id INT NOT NULL, INDEX IDX_AEA44DCC2E9710B0 (jouet_id), INDEX IDX_AEA44DCC3FC0D758 (mecanisme_id), PRIMARY KEY(jouet_id, mecanisme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jouet_recompense (jouet_id INT NOT NULL, recompense_id INT NOT NULL, INDEX IDX_7BF475242E9710B0 (jouet_id), INDEX IDX_7BF475244D714096 (recompense_id), PRIMARY KEY(jouet_id, recompense_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jouet_jouet (jouet_source INT NOT NULL, jouet_target INT NOT NULL, INDEX IDX_93CF4E376FC7722B (jouet_source), INDEX IDX_93CF4E37762222A4 (jouet_target), PRIMARY KEY(jouet_source, jouet_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_FCF22AF4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste_jouet (liste_id INT NOT NULL, jouet_id INT NOT NULL, INDEX IDX_74C62B76E85441D8 (liste_id), INDEX IDX_74C62B762E9710B0 (jouet_id), PRIMARY KEY(liste_id, jouet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mecanisme (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, jouet_id INT DEFAULT NULL, note DOUBLE PRECISION NOT NULL, INDEX IDX_CFBDFA14A76ED395 (user_id), INDEX IDX_CFBDFA142E9710B0 (jouet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE password_token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(50) NOT NULL, expires_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_BEAB6C245F37A13B (token), INDEX IDX_BEAB6C24A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personne (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', facebook VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_FCEC9EF3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personne_jouet (personne_id INT NOT NULL, jouet_id INT NOT NULL, INDEX IDX_7FDCEEB1A21BD112 (personne_id), INDEX IDX_7FDCEEB12E9710B0 (jouet_id), PRIMARY KEY(personne_id, jouet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pret (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, jouet_id INT DEFAULT NULL, date_fin DATETIME NOT NULL, observation VARCHAR(255) DEFAULT NULL, date_debut DATETIME NOT NULL, retourner TINYINT(1) NOT NULL, INDEX IDX_52ECE979A76ED395 (user_id), INDEX IDX_52ECE9792E9710B0 (jouet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recompense (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1E9BC0DE3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, username VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, bloquer TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jouets_collection (user_id INT NOT NULL, jouet_id INT NOT NULL, INDEX IDX_DF307784A76ED395 (user_id), INDEX IDX_DF3077842E9710B0 (jouet_id), PRIMARY KEY(user_id, jouet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jouets_wish (user_id INT NOT NULL, jouet_id INT NOT NULL, INDEX IDX_2687070A76ED395 (user_id), INDEX IDX_26870702E9710B0 (jouet_id), PRIMARY KEY(user_id, jouet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_user (user_source INT NOT NULL, user_target INT NOT NULL, INDEX IDX_F7129A803AD8644E (user_source), INDEX IDX_F7129A80233D34C1 (user_target), PRIMARY KEY(user_source, user_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, jouet_id INT DEFAULT NULL, user_id INT DEFAULT NULL, lien VARCHAR(255) NOT NULL, INDEX IDX_7CC7DA2C2E9710B0 (jouet_id), INDEX IDX_7CC7DA2CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C01551433DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC2E9710B0 FOREIGN KEY (jouet_id) REFERENCES jouet (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE jouet ADD CONSTRAINT FK_6B3DFFD8BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jouet ADD CONSTRAINT FK_6B3DFFD83DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE jouet_theme ADD CONSTRAINT FK_6F8756E72E9710B0 FOREIGN KEY (jouet_id) REFERENCES jouet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jouet_theme ADD CONSTRAINT FK_6F8756E759027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jouet_mecanisme ADD CONSTRAINT FK_AEA44DCC2E9710B0 FOREIGN KEY (jouet_id) REFERENCES jouet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jouet_mecanisme ADD CONSTRAINT FK_AEA44DCC3FC0D758 FOREIGN KEY (mecanisme_id) REFERENCES mecanisme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jouet_recompense ADD CONSTRAINT FK_7BF475242E9710B0 FOREIGN KEY (jouet_id) REFERENCES jouet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jouet_recompense ADD CONSTRAINT FK_7BF475244D714096 FOREIGN KEY (recompense_id) REFERENCES recompense (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jouet_jouet ADD CONSTRAINT FK_93CF4E376FC7722B FOREIGN KEY (jouet_source) REFERENCES jouet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jouet_jouet ADD CONSTRAINT FK_93CF4E37762222A4 FOREIGN KEY (jouet_target) REFERENCES jouet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE liste ADD CONSTRAINT FK_FCF22AF4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE liste_jouet ADD CONSTRAINT FK_74C62B76E85441D8 FOREIGN KEY (liste_id) REFERENCES liste (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE liste_jouet ADD CONSTRAINT FK_74C62B762E9710B0 FOREIGN KEY (jouet_id) REFERENCES jouet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA142E9710B0 FOREIGN KEY (jouet_id) REFERENCES jouet (id)');
        $this->addSql('ALTER TABLE password_token ADD CONSTRAINT FK_BEAB6C24A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EF3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE personne_jouet ADD CONSTRAINT FK_7FDCEEB1A21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personne_jouet ADD CONSTRAINT FK_7FDCEEB12E9710B0 FOREIGN KEY (jouet_id) REFERENCES jouet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pret ADD CONSTRAINT FK_52ECE979A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pret ADD CONSTRAINT FK_52ECE9792E9710B0 FOREIGN KEY (jouet_id) REFERENCES jouet (id)');
        $this->addSql('ALTER TABLE recompense ADD CONSTRAINT FK_1E9BC0DE3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE jouets_collection ADD CONSTRAINT FK_DF307784A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jouets_collection ADD CONSTRAINT FK_DF3077842E9710B0 FOREIGN KEY (jouet_id) REFERENCES jouet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jouets_wish ADD CONSTRAINT FK_2687070A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jouets_wish ADD CONSTRAINT FK_26870702E9710B0 FOREIGN KEY (jouet_id) REFERENCES jouet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A803AD8644E FOREIGN KEY (user_source) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A80233D34C1 FOREIGN KEY (user_target) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C2E9710B0 FOREIGN KEY (jouet_id) REFERENCES jouet (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143A76ED395');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C01551433DA5256D');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC2E9710B0');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EA76ED395');
        $this->addSql('ALTER TABLE invitation DROP FOREIGN KEY FK_F11D61A2A76ED395');
        $this->addSql('ALTER TABLE jouet DROP FOREIGN KEY FK_6B3DFFD8BCF5E72D');
        $this->addSql('ALTER TABLE jouet DROP FOREIGN KEY FK_6B3DFFD83DA5256D');
        $this->addSql('ALTER TABLE jouet_theme DROP FOREIGN KEY FK_6F8756E72E9710B0');
        $this->addSql('ALTER TABLE jouet_theme DROP FOREIGN KEY FK_6F8756E759027487');
        $this->addSql('ALTER TABLE jouet_mecanisme DROP FOREIGN KEY FK_AEA44DCC2E9710B0');
        $this->addSql('ALTER TABLE jouet_mecanisme DROP FOREIGN KEY FK_AEA44DCC3FC0D758');
        $this->addSql('ALTER TABLE jouet_recompense DROP FOREIGN KEY FK_7BF475242E9710B0');
        $this->addSql('ALTER TABLE jouet_recompense DROP FOREIGN KEY FK_7BF475244D714096');
        $this->addSql('ALTER TABLE jouet_jouet DROP FOREIGN KEY FK_93CF4E376FC7722B');
        $this->addSql('ALTER TABLE jouet_jouet DROP FOREIGN KEY FK_93CF4E37762222A4');
        $this->addSql('ALTER TABLE liste DROP FOREIGN KEY FK_FCF22AF4A76ED395');
        $this->addSql('ALTER TABLE liste_jouet DROP FOREIGN KEY FK_74C62B76E85441D8');
        $this->addSql('ALTER TABLE liste_jouet DROP FOREIGN KEY FK_74C62B762E9710B0');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14A76ED395');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA142E9710B0');
        $this->addSql('ALTER TABLE password_token DROP FOREIGN KEY FK_BEAB6C24A76ED395');
        $this->addSql('ALTER TABLE personne DROP FOREIGN KEY FK_FCEC9EF3DA5256D');
        $this->addSql('ALTER TABLE personne_jouet DROP FOREIGN KEY FK_7FDCEEB1A21BD112');
        $this->addSql('ALTER TABLE personne_jouet DROP FOREIGN KEY FK_7FDCEEB12E9710B0');
        $this->addSql('ALTER TABLE pret DROP FOREIGN KEY FK_52ECE979A76ED395');
        $this->addSql('ALTER TABLE pret DROP FOREIGN KEY FK_52ECE9792E9710B0');
        $this->addSql('ALTER TABLE recompense DROP FOREIGN KEY FK_1E9BC0DE3DA5256D');
        $this->addSql('ALTER TABLE jouets_collection DROP FOREIGN KEY FK_DF307784A76ED395');
        $this->addSql('ALTER TABLE jouets_collection DROP FOREIGN KEY FK_DF3077842E9710B0');
        $this->addSql('ALTER TABLE jouets_wish DROP FOREIGN KEY FK_2687070A76ED395');
        $this->addSql('ALTER TABLE jouets_wish DROP FOREIGN KEY FK_26870702E9710B0');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A803AD8644E');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A80233D34C1');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C2E9710B0');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2CA76ED395');
        $this->addSql('DROP TABLE blog');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE invitation');
        $this->addSql('DROP TABLE jouet');
        $this->addSql('DROP TABLE jouet_theme');
        $this->addSql('DROP TABLE jouet_mecanisme');
        $this->addSql('DROP TABLE jouet_recompense');
        $this->addSql('DROP TABLE jouet_jouet');
        $this->addSql('DROP TABLE liste');
        $this->addSql('DROP TABLE liste_jouet');
        $this->addSql('DROP TABLE mecanisme');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE password_token');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE personne_jouet');
        $this->addSql('DROP TABLE pret');
        $this->addSql('DROP TABLE recompense');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE jouets_collection');
        $this->addSql('DROP TABLE jouets_wish');
        $this->addSql('DROP TABLE user_user');
        $this->addSql('DROP TABLE video');
    }
}
