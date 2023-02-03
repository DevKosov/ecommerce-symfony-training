<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230203103819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D9D86650F');
        $this->addSql('DROP INDEX IDX_6EEAA67D9D86650F ON commande');
        $this->addSql('ALTER TABLE commande CHANGE user_id_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DA76ED395 ON commande (user_id)');
        $this->addSql('ALTER TABLE ligne_commande MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B9AF8E3A3');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74BD71E064B');
        $this->addSql('DROP INDEX IDX_3170B74B9AF8E3A3 ON ligne_commande');
        $this->addSql('DROP INDEX IDX_3170B74BD71E064B ON ligne_commande');
        $this->addSql('DROP INDEX `primary` ON ligne_commande');
        $this->addSql('ALTER TABLE ligne_commande ADD product_id INT NOT NULL, ADD commande_id INT NOT NULL, DROP id, DROP id_article_id, DROP id_commande_id');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_3170B74B4584665A ON ligne_commande (product_id)');
        $this->addSql('CREATE INDEX IDX_3170B74B82EA2E54 ON ligne_commande (commande_id)');
        $this->addSql('ALTER TABLE ligne_commande ADD PRIMARY KEY (product_id, commande_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395');
        $this->addSql('DROP INDEX IDX_6EEAA67DA76ED395 ON commande');
        $this->addSql('ALTER TABLE commande CHANGE user_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6EEAA67D9D86650F ON commande (user_id_id)');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B4584665A');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B82EA2E54');
        $this->addSql('DROP INDEX IDX_3170B74B4584665A ON ligne_commande');
        $this->addSql('DROP INDEX IDX_3170B74B82EA2E54 ON ligne_commande');
        $this->addSql('ALTER TABLE ligne_commande ADD id INT AUTO_INCREMENT NOT NULL, ADD id_article_id INT NOT NULL, ADD id_commande_id INT NOT NULL, DROP product_id, DROP commande_id, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B9AF8E3A3 FOREIGN KEY (id_commande_id) REFERENCES commande (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74BD71E064B FOREIGN KEY (id_article_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3170B74B9AF8E3A3 ON ligne_commande (id_commande_id)');
        $this->addSql('CREATE INDEX IDX_3170B74BD71E064B ON ligne_commande (id_article_id)');
    }
}
