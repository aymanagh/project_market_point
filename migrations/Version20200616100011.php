<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200616100011 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE market_order (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, workshop_id INT NOT NULL, ot VARCHAR(255) NOT NULL, total_price DOUBLE PRECISION NOT NULL, add_at DATETIME NOT NULL, product_quantity LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_3E072CEDF4D4D0B2 (ot), INDEX IDX_3E072CEDA76ED395 (user_id), INDEX IDX_3E072CED1FDCE57C (workshop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE op (id INT AUTO_INCREMENT NOT NULL, production_line_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_F3B914AB586EF89F (production_line_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, product_categorie_id INT NOT NULL, workshop_id INT NOT NULL, product_line_id INT NOT NULL, op_id INT DEFAULT NULL, reference VARCHAR(255) NOT NULL, designation VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, warning_level VARCHAR(255) NOT NULL, waiting VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, amount VARCHAR(255) NOT NULL, update_at DATETIME NOT NULL, image_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D34A04ADAEA34913 (reference), INDEX IDX_D34A04AD3151C546 (product_categorie_id), INDEX IDX_D34A04AD1FDCE57C (workshop_id), INDEX IDX_D34A04AD9CA26EF2 (product_line_id), INDEX IDX_D34A04AD2F7FAB3F (op_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_27DD60B95E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE production_line (id INT AUTO_INCREMENT NOT NULL, workshop_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_A04FFE21FDCE57C (workshop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, workshop_id INT NOT NULL, ipn VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, badge VARCHAR(255) DEFAULT NULL, status INT NOT NULL, add_at DATETIME NOT NULL, access TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D6493D721C14 (ipn), UNIQUE INDEX UNIQ_8D93D6495126AC48 (mail), INDEX IDX_8D93D6491FDCE57C (workshop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workshop (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_9B6F02C45E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE market_order ADD CONSTRAINT FK_3E072CEDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE market_order ADD CONSTRAINT FK_3E072CED1FDCE57C FOREIGN KEY (workshop_id) REFERENCES workshop (id)');
        $this->addSql('ALTER TABLE op ADD CONSTRAINT FK_F3B914AB586EF89F FOREIGN KEY (production_line_id) REFERENCES production_line (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD3151C546 FOREIGN KEY (product_categorie_id) REFERENCES product_categorie (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD1FDCE57C FOREIGN KEY (workshop_id) REFERENCES workshop (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD9CA26EF2 FOREIGN KEY (product_line_id) REFERENCES production_line (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD2F7FAB3F FOREIGN KEY (op_id) REFERENCES op (id)');
        $this->addSql('ALTER TABLE production_line ADD CONSTRAINT FK_A04FFE21FDCE57C FOREIGN KEY (workshop_id) REFERENCES workshop (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491FDCE57C FOREIGN KEY (workshop_id) REFERENCES workshop (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD2F7FAB3F');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD3151C546');
        $this->addSql('ALTER TABLE op DROP FOREIGN KEY FK_F3B914AB586EF89F');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD9CA26EF2');
        $this->addSql('ALTER TABLE market_order DROP FOREIGN KEY FK_3E072CEDA76ED395');
        $this->addSql('ALTER TABLE market_order DROP FOREIGN KEY FK_3E072CED1FDCE57C');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD1FDCE57C');
        $this->addSql('ALTER TABLE production_line DROP FOREIGN KEY FK_A04FFE21FDCE57C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491FDCE57C');
        $this->addSql('DROP TABLE market_order');
        $this->addSql('DROP TABLE op');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_categorie');
        $this->addSql('DROP TABLE production_line');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE workshop');
    }
}
